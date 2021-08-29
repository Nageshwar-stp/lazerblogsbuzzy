<span class="back-to-top hide-mobile"><i class="material-icons">&#xE316;</i></span>

<div class="clear"></div>
<footer class="footer-bottom category-dropdown_sec sec_cat3 clearfix clearfix">
    <div class="container">
        <img class="footer-site-logo" src="{{ asset(get_buzzy_config('footerlogo')) }}" width="60px" alt="">

        <div class="footer-left">
            <div class="footer-menu clearfix">
                {{ menu('footer-menu', array(
                    'a_class' => 'footer-menu__item'
                )) }}
            </div>
            <div class="footer-copyright clearfix">
                {{ trans("updates.copyright") }}
            </div>
        </div>

        @include('_particles.header_language_picker')
    </div>
</footer>
