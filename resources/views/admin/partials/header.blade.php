<!-- Navbar-->
<header class="app-header">
  <a class="app-header__logo" target="_blank" rel="noopener noreferrer" href="{{ config('app.url') }}">{{ config('app.name') }}</a>
  <!-- Sidebar toggle button-->
  <a class="app-sidebar__toggle" href="#" data-toggle="sidebar" aria-label="Hide Sidebar"></a>
  <!-- Navbar Right Menu-->
  <ul class="app-nav">
    {{-- <li class="app-search">
      <input class="app-search__input" type="search" placeholder="Search" />
      <button class="app-search__button">
        <i class="fa fa-search"></i>
      </button>
    </li> --}}
    <!--Notification Menu-->
    <li class="dropdown order-notifications">
      <a class="app-nav__item" href="#" data-toggle="dropdown" aria-label="Show notifications">
        <i data-count="0" class="fa fa-bell-o fa-lg notification-icon"></i>
      </a>
      <ul class="app-notification dropdown-menu dropdown-menu-right">
        <li class="app-notification__title">
          (<span class="notif-count">0</span>) New Orders has been placed.
        </li>
        <div class="app-notification__content">
        </div>
        <li class="app-notification__footer">
          <a href="#">See all notifications.</a>
        </li>
      </ul>

    </li>
    <!-- User Menu-->
    <li class="dropdown">
      <a class="app-nav__item" href="#" data-toggle="dropdown" aria-label="Open Profile Menu">
        <i class="fa fa-user fa-lg"></i>
      </a>
      <ul class="dropdown-menu settings-menu dropdown-menu-right">
        <li>
          <a class="dropdown-item" href="{{ route('admin.settings') }}"><i class="fa fa-cog fa-lg"></i>Settings</a>
        </li>
        <li>
          <a class="dropdown-item" href="#"><i class="fa fa-user fa-lg"></i>Profile</a>
        </li>
        <li>
          <a class="dropdown-item" href="{{ route('admin.logout') }}"><i class="fa fa-sign-out fa-lg"></i>Logout</a>
        </li>
      </ul>
    </li>
  </ul>
</header>
@push('scripts')

<script type="text/javascript">
  var notificationsWrapper   = $('.order-notifications');
  var notificationsToggle    = notificationsWrapper.find('a[data-toggle]');
  var notificationsCountElem = notificationsToggle.find('i[data-count]');
  var notificationsCount     = parseInt(notificationsCountElem.data('count'));
  var notifications          = notificationsWrapper.find('div.app-notification__content');

  if (notificationsCount <= 0) {
    notificationsWrapper.hide();
  }

  // Enable pusher logging - don't include this in production
  // Pusher.logToConsole = true;

  var pusher = new Pusher('409d39d739305c14f556', {
    cluster: 'ap2',
    encrypted: true
  });
            
  // Subscribe to the channel we specified in our Laravel Event
  var channel = pusher.subscribe('order.placed');

  // Bind a function to a Event (the full Laravel class)
  // this is called when the event notification is received
  channel.bind('App\\Events\\OrderPlaced', function(data) {       
    var existingNotifications = notifications.html();
    var avatar = Math.floor(Math.random() * (71 - 20 + 1)) + 20;
    var newNotificationHtml = `
          <li>
            <a class="app-notification__item" href="javascript:;">
              <span class="app-notification__icon">
                <span class="fa-stack fa-lg">
                  <i class="fa fa-circle fa-stack-2x text-success"></i>
                  <i class="fa fa-money fa-stack-1x fa-inverse"></i>
                </span>
              </span>
              <div>
                <p class="app-notification__message">`
                   + data.message + `
                </p>
                <p class="app-notification__meta">At `+  new Date().toLocaleTimeString() +`</p>
              </div>
            </a>
          </li>      
    `;
    notifications.html(newNotificationHtml + existingNotifications);

    notificationsCount += 1;
    notificationsCountElem.attr('data-count', notificationsCount);
    notificationsWrapper.find('.notif-count').text(notificationsCount);
    notificationsWrapper.show();
  });
</script>

@endpush
