<ul class="uk-nav uk-nav-default">
    <li class="uk-nav-header">Account</li>
    <li class="{{ $active == 'profile' ? 'uk-active' : '' }}"><a href="{{ url('account/profile') }}"><span class="uk-margin-small-right" uk-icon="icon: user"></span>Profile</a></li>
    <li class="{{ $active == 'password' ? 'uk-active' : '' }}"><a href="{{ url('account/settings') }}"><span class="uk-margin-small-right" uk-icon="icon: cog"></span>Settings</a></li>
    <li class="{{ $active == 'memberships' ? 'uk-active' : '' }}"><a href="{{ url('account/memberships') }}"><span class="uk-margin-small-right" uk-icon="icon: social"></span>Memberships</a></li>
    <li class="{{ $active == 'orders' ? 'uk-active' : '' }}"><a href="{{ url('account/orders') }}"><span class="uk-margin-small-right" uk-icon="icon: cart"></span>Orders</a></li>
    <li class="{{ $active == 'billing' ? 'uk-active' : '' }}"><a href="{{ url('account/billing') }}"><span class="uk-margin-small-right" uk-icon="icon: credit-card"></span>Billing</a></li>
    <li class="{{ $active == 'notifications' ? 'uk-active' : '' }}"><a href="{{ url('account/notifications') }}"><span class="uk-margin-small-right" uk-icon="icon: mail"></span>Notification Settings</a></li>
{{--    <li class="{{ $active == 'referral' ? 'uk-active' : '' }}"><a href="{{ url('account/referral') }}"><span class="uk-margin-small-right" uk-icon="icon: social"></span>Share DGT</a></li>--}}
</ul>

