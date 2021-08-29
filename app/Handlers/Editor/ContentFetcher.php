<?php

namespace App\Handlers\Editor;

use GuzzleHttp\Client;

class ContentFetcher
{
    public function run($url)
    {
        $client = new Client;
        $response = $client->get(
            $url,
            [
                'timeout' => 30,
                'allow_redirects' => true
            ]
        );

        if (200 !== $response->getStatusCode()) {
            return array('status' => 'error', 'title' => trans('updates.error'), 'error' => trans('updates.nodata'));
        }

        $homepage = (string) $response->getBody()->getContents();

        if (!$homepage) {
            return array('status' => 'error', 'title' => trans('updates.error'), 'error' => trans('updates.nodata'));
        }

        $tags = $this->getMetaTags($homepage);

        if (empty($tags)) {
            return array('status' => 'error', 'title' => trans('updates.error'), 'error' => trans('updates.nodata'));
        }

        preg_match_all('#<article(.*)>(.*)</article>#isU', $homepage, $matches_article);

        if (count($matches_article)) {
            $article = '';
            foreach ($matches_article[0] as $value) {
                $article .= $value;
            }
            $homepage = $article;
        }

        preg_match_all('#<p(.*)>(.*)</p>#isU', $homepage, $matches);

        $data = [];
        $title = null;
        $image = null;
        $description = null;
        $body = null;

        foreach ($matches[0] as $value) {
            $body .= $value;
        }

        if (isset($tags['title'])) {
            $title = $tags['title'];
        } elseif (isset($tags['twitter:title'])) {
            $title = $tags['twitter:title'];
        } elseif (isset($tags['article:title'])) {
            $title = $tags['article:title'];
        }

        if (isset($tags['og:image'])) {
            $image = $tags['og:image'];
        } elseif (isset($tags['twitter:image'])) {
            $image = $tags['twitter:image'];
        } elseif (isset($tags['article:image'])) {
            $image = $tags['article:image'];
        } elseif (isset($tags['image'])) {
            $image = $tags['image'];
        }

        if (isset($tags['og:description'])) {
            $description = $tags['og:description'];
        } elseif (isset($tags['description'])) {
            $description = $tags['description'];
        } elseif (isset($tags['article:description'])) {
            $description = $tags['article:description'];
        }


        $data['headline'] = strip_tags($title);
        $data['description'] = strip_tags($description);
        $data['preview'] = $image;

        $allowed_tags = array(
            "<a>", "<b>", "<strong>", "<br>", "<span>", "<em>", "<img>", "<hr>", "<i>",
            "<h1>", "<h2>", "<h3>", "<h4>", "<h5>", "<h6>",
            "<li>", "<ol>", "<p>", "<s>", "<span>", "<u>", "<ul>",
            "<code>", "<time>", "<data>", "<abbr>", "<dfn>", "<q>", "<cite>", "<s>", "<small>",
            "<strong>", "<em>", "<a>", "<figcaption>", "<figure>", "<dd>", "<dt>",
            "<dl>",  "<blockquote>", "<pre>", "<address>",
            "<th>", "<td>", "<tr>", "<tfoot>", "<thead>", "<tbody>",
        );
        $data['content'] = strip_tags($body, $allowed_tags);

        $entry = new \stdClass();
        $entry->title = $title;
        $entry->body = $data['content'];

        $data['entries'] = view('_forms.__addtextform')->with(compact('entry'))->render();

        return $data;
    }

    public function getMetaTags($str)
    {
        $pattern = '
      ~<\s*meta\s

      # using lookahead to capture type to $1
        (?=[^>]*?
        \b(?:name|property|http-equiv)\s*=\s*
        (?|"\s*([^"]*?)\s*"|\'\s*([^\']*?)\s*\'|
        ([^"\'>]*?)(?=\s*/?\s*>|\s\w+\s*=))
      )

      # capture content to $2
      [^>]*?\bcontent\s*=\s*
        (?|"\s*([^"]*?)\s*"|\'\s*([^\']*?)\s*\'|
        ([^"\'>]*?)(?=\s*/?\s*>|\s\w+\s*=))
      [^>]*>

      ~ix';

        if (preg_match_all($pattern, $str, $out))
            return array_combine($out[1], $out[2]);
        return array();
    }
}
