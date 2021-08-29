<?php

namespace App\Jobs;

use App\Feed;
use App\Post;
use App\Entry;
use Carbon\Carbon;
use App\Managers\UploadManager;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use App\Handlers\Editor\ContentFetcher;
use Illuminate\Queue\InteractsWithQueue;
use Vedmant\FeedReader\Facades\FeedReader;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class FetchFeedPosts implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $feed;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Feed $feed)
    {
        $this->feed = $feed;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $f = FeedReader::read($this->feed->url);

            if ($f) {
                collect($f->get_items())->slice(0, $this->feed->post_fetch_count)
                    ->each(function ($feed_item) {
                        $unqid = $feed_item->get_id();
                        if (!Entry::where('video', $unqid)->first()) {
                            if ($this->feed->checked_at === null || Carbon::create($feed_item->get_date())->greaterThan($this->feed->checked_at->toDateTimeString())) {
                                $content_fetcher = new ContentFetcher();
                                $content = $content_fetcher->run($feed_item->get_link());
                                if (isset($content['status']) && $content['status'] === 'error') {
                                    return;
                                }
                                $post = new Post();
                                $post->title = $feed_item->get_title();
                                $post->slug = sanitize_title_with_dashes($post->title);
                                $post->body = isset($content['description']) ? $content['description'] : '';
                                $post->type = 'news';
                                $post->user_id = $this->feed->post_user_id;
                                $post->tags = isset($content['tags']) ? $content['tags'] : '';
                                $post->ordertype = null;
                                $post->pagination = null;
                                $post->published_at = now();
                                $post->language = isset($this->feed->language) ? $this->feed->language  : get_buzzy_config('sitedefaultlanguage', 'en');


                                if (!empty($content['preview'])) {
                                    $image = new UploadManager();
                                    $image->path('upload/media/posts');
                                    $image->name($post->slug . '_' . time());
                                    $image->setUrlFile($content['preview']);
                                    $image->make();
                                    $image->mime('jpg');
                                    $image->save(
                                        [
                                            'fit_width' => config('buzzytheme_' . get_buzzy_config('CurrentTheme') . '.preview-image_big_width', 768),
                                            'fit_height' => config('buzzytheme_' . get_buzzy_config('CurrentTheme') . '.preview-image_big_height', 440),
                                            'image_size' => 'b',
                                        ]
                                    ); // move big image
                                    $image->save(
                                        [
                                            'fit_width' => config('buzzytheme_' . get_buzzy_config('CurrentTheme') . '.preview-image_small_width', 400),
                                            'fit_height' => config('buzzytheme_' . get_buzzy_config('CurrentTheme') . '.preview-image_small_height', 266),
                                            'image_size' => 's',
                                        ]
                                    );

                                    $post->thumb = $image->getPathforSave();
                                }

                                $post->approve = 'yes';
                                $post->save();

                                $post->categories()->sync(explode(',', $this->feed->post_categories));

                                $entry = new Entry;
                                $entry->user_id = $post->user_id;
                                $entry->post_id = $post->id;
                                $entry->type = 'text';
                                $entry->order = 0;
                                $entry->title = null;
                                $entry->body = $this->feed->content_fetcher === 'custom' ? $content['content'] : $feed_item->get_content();;
                                $entry->source = null;
                                $entry->image = null;
                                $entry->video = $unqid;
                                $entry->save();
                            }
                        }
                    });
            }
        } catch (\Exception $th) {
            //
        }

        $this->feed->checked_at = now();

        return $this->feed->save();
    }
}
