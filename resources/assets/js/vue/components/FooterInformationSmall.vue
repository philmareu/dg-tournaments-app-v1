<template>
    <div class="uk-padding-small">
        <nav uk-grid>
            <div class="uk-width-1-2">
                <h3>About</h3>
                <ul class="uk-list">
                    <li><a :href="url('about')">About DGT</a></li>
                    <li><a :href="url('blog')">Blog</a></li>
                    <li><a :href="url('contact-us')">Contact Us</a></li>
                </ul>
            </div>
            <div class="uk-width-1-2">
                <h3>Search</h3>
                <ul class="uk-list">
                    <li><a :href="url('search')">Tournaments</a></li>
                </ul>
            </div>
            <div class="uk-width-1-2">
                <h3>Legal</h3>
                <ul class="uk-list">
                    <li><a :href="url('terms-of-service')">Terms of Service</a></li>
                    <li><a :href="url('privacy-policy')">Privacy Policy</a></li>
                </ul>
            </div>
        </nav>

        <h3>Subscribe to our newsletter</h3>

        <form action="//dgtournaments.us15.list-manage.com/subscribe/post?u=ede4754794a552aabc331e9de&amp;id=f163d157a4" method="POST" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate uk-form" target="_blank" novalidate>
            <div uk-grid>
                <div>
                    <input type="email" value="" name="EMAIL" class="email uk-input" id="mce-EMAIL" placeholder="email address" required>
                </div>
                <div>
                    <input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="uk-button uk-button-default">
                </div>
            </div>

            <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
            <div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="b_ede4754794a552aabc331e9de_f163d157a4" tabindex="-1" value=""></div>
        </form>

        <h3>Connect. We are very social!</h3>

        <div class="social">
            <a href="http://instagram.com/dgtournaments" uk-icon="icon: instagram;"></a>
            <a href="http://facebook.com/dgtournaments" uk-icon="icon: facebook;"></a>
            <a href="http://twitter.com/dgtournaments1" uk-icon="icon: twitter;"></a>
        </div>

        <div class="copyright">
            <p class="uk-text-center uk-margin-remove">
                &copy; 2017-2018 DG Tournaments - Event data &copy; 2017-2018 <a href="http://pdga.com" target="_blank">PDGA</a>
            </p>
        </div>
    </div>
</template>

<script>
    
    import Helpers from '../mixins/helpers';
    
    export default {
        
        mixins: [
            Helpers
        ]
        
    }
    
</script>