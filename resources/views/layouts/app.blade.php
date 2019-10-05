<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>
        Page Titile - category_1 - SmartAdmin v4.0.1
    </title>
    <meta name="description" content="Page Titile">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no, minimal-ui">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Call App Mode on ios devices -->
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <!-- Remove Tap Highlight on Windows Phone IE -->
    <meta name="msapplication-tap-highlight" content="no">
    <!-- base css -->
    <link rel="stylesheet" media="screen, print" href="/css/vendors.bundle.css">
    <link rel="stylesheet" media="screen, print" href="/css/app.bundle.css">
    <link rel="stylesheet" media="screen, print" href="/css/miscellaneous/fullcalendar/fullcalendar.bundle.css">
    <link rel="stylesheet" media="screen, print" href="/css/formplugins/bootstrap-datepicker/bootstrap-datepicker.css">
    <link rel="stylesheet" media="screen, print" href="/css/estilo.css">
    <link rel="stylesheet" media="screen, print" href="/css/fa-regular.css">
    <link rel="stylesheet" media="screen, print" href="/css/fa-solid.css">
    <link rel="stylesheet" media="screen, print" href="/css/fa-brands.css">
    <link rel="stylesheet" media="screen, print" href="/css/formplugins/select2/select2.bundle.css">
    <!-- Place favicon.ico in the root directory -->

    <link rel="apple-touch-icon" sizes="180x180" href="/img/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/img/favicon/favicon-32x32.png">
    <link rel="mask-icon" href="/img/favicon/safari-pinned-tab.svg" color="#5bbad5">
    <!--<link rel="stylesheet" media="screen, print" href="css/your_styles.css">-->
    <title>SMART - NovoPles</title>
</head>
<body class="mod-bg-1 ">
<!-- DOC: script to save and load page settings -->
<script>
    /**
     *	This script should be placed right after the body tag for fast execution
     *	Note: the script is written in pure javascript and does not depend on thirdparty library
     **/
    'use strict';

    var classHolder = document.getElementsByTagName("BODY")[0],
        /**
         * Load from localstorage
         **/
        themeSettings = (localStorage.getItem('themeSettings')) ? JSON.parse(localStorage.getItem('themeSettings')) :
            {},
        themeURL = themeSettings.themeURL || '',
        themeOptions = themeSettings.themeOptions || '';
    /**
     * Load theme options
     **/
    if (themeSettings.themeOptions)
    {
        classHolder.className = themeSettings.themeOptions;
        console.log("%c✔ Theme settings loaded", "color: #148f32");
    }
    else
    {
        console.log("Heads up! Theme settings is empty or does not exist, loading default settings...");
    }
    if (themeSettings.themeURL && !document.getElementById('mytheme'))
    {
        var cssfile = document.createElement('link');
        cssfile.id = 'mytheme';
        cssfile.rel = 'stylesheet';
        cssfile.href = themeURL;
        document.getElementsByTagName('head')[0].appendChild(cssfile);
    }
    /**
     * Save to localstorage
     **/
    var saveSettings = function()
    {
        themeSettings.themeOptions = String(classHolder.className).split(/[^\w-]+/).filter(function(item)
        {
            return /^(nav|header|mod|display)-/i.test(item);
        }).join(' ');
        if (document.getElementById('mytheme'))
        {
            themeSettings.themeURL = document.getElementById('mytheme').getAttribute("href");
        };
        localStorage.setItem('themeSettings', JSON.stringify(themeSettings));
    }
    /**
     * Reset settings
     **/
    var resetSettings = function()
    {
        localStorage.setItem("themeSettings", "");
    }

</script>
<!-- BEGIN Page Wrapper -->
<div class="page-wrapper">
    <div class="page-inner">
        <!-- BEGIN Left Aside -->
        <aside class="page-sidebar">
            <div class="page-logo">
                <a href="#" class="page-logo-link press-scale-down d-flex align-items-center position-relative" data-toggle="modal" data-target="#modal-shortcut">
                    <img src="/img/logo.png" alt="SmartAdmin WebApp" aria-roledescription="logo">
                    <span class="page-logo-text mr-1">SMART</span>
                    <span class="position-absolute text-white opacity-50 small pos-top pos-right mr-2 mt-n2">NovoPles</span>
                    <i class="fal fa-angle-down d-inline-block ml-1 fs-lg color-primary-300"></i>
                </a>
            </div>
            <!-- BEGIN PRIMARY NAVIGATION -->
            <nav id="js-primary-nav" class="primary-nav" role="navigation">
                <div class="nav-filter">
                    <div class="position-relative">
                        <input type="text" id="nav_filter_input" placeholder="Filter menu" class="form-control" tabindex="0">
                        <a href="#" onclick="return false;" class="btn-primary btn-search-close js-waves-off" data-action="toggle" data-class="list-filter-active" data-target=".page-sidebar">
                            <i class="fal fa-chevron-up"></i>
                        </a>
                    </div>
                </div>
                <div class="info-card">
                    <img src="/storage/profile/{{$user->profile}}" class="profile-image rounded-circle" alt="{{$user->name}}">
                    <div class="info-card-text">
                        <a href="#" class="d-flex align-items-center text-white" data-toggle="modal" data-target=".profile">
                            <span class="text-truncate text-truncate-sm d-inline-block">
                                {{$user->name}}
                            </span>
                        </a>
                        <span class="d-inline-block text-truncate text-truncate-sm">{{$user->email}}</span>
                    </div>
                    <img src="/img/card-backgrounds/cover-2-lg.png" class="cover" alt="cover">
                    <a href="#" onclick="return false;" class="pull-trigger-btn" data-action="toggle" data-class="list-filter-active" data-target=".page-sidebar" data-focus="nav_filter_input">
                        <i class="fal fa-angle-down"></i>
                    </a>
                </div>
                <ul id="js-nav-menu" class="nav-menu">
                    <li>
                        <!-- Esto es un Menu -->
                        <a href="/home" title="Inicio" data-filter-tags="Inicio">
                            <i class="fal fa-home"></i>
                            <span class="nav-link-text" data-i18n="nav.application_intel">Inicio</span>
                        </a>
                    </li>
                    @foreach($menus as $item)
                        <!-- Esto es un Menu -->
                        <li>
                            <a href="#" title="{{$item["name"]}}" data-filter-tags="{{$item["name"]}}">
                                <i class="{{$item["icon"]}}"></i>
                                <span class="nav-link-text" data-i18n="nav.application_intel">{{$item["name"]}}</span>
                            </a>
                            <ul>
                                @foreach($item["hijos"] as $item)
                                    <li>
                                        <a href="{{$item["url"]}}" title="{{$item["name"]}}" data-filter-tags="{{$item["name"]}}">
                                            <span class="nav-link-text" data-i18n="nav.application_intel_introduction">{{$item["name"]}}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @endforeach
                </ul>
                <div class="filter-message js-filter-message bg-success-600"></div>
            </nav>
            <!-- END PRIMARY NAVIGATION -->
            <!-- NAV FOOTER -->
            <div class="nav-footer shadow-top">
                <a href="#" onclick="return false;" data-action="toggle" data-class="nav-function-minify" class="hidden-md-down">
                    <i class="ni ni-chevron-right"></i>
                    <i class="ni ni-chevron-right"></i>
                </a>
                <ul class="list-table m-auto nav-footer-buttons">

                </ul>
            </div> <!-- END NAV FOOTER -->
        </aside>
        <!-- END Left Aside -->
        <div class="page-content-wrapper">
            <!-- BEGIN Page Header -->
            <header class="page-header" role="banner">
                <!-- we need this logo when user switches to nav-function-top -->
                <div class="page-logo">
                    <a href="#" class="page-logo-link press-scale-down d-flex align-items-center position-relative" data-toggle="modal" data-target="#modal-shortcut">
                        <img src="/img/logo.png" alt="SmartAdmin WebApp" aria-roledescription="logo">
                        <span class="page-logo-text mr-1">SMART</span>
                        <span class="position-absolute text-white opacity-50 small pos-top pos-right mr-2 mt-n2">NovoPles</span>
                        <i class="fal fa-angle-down d-inline-block ml-1 fs-lg color-primary-300"></i>
                    </a>
                </div>
                <!-- DOC: nav menu layout change shortcut -->
                <div class="hidden-md-down dropdown-icon-menu position-relative">
                    <a href="#" class="header-btn btn js-waves-off" data-action="toggle" data-class="nav-function-hidden" title="Ocultar navegacion">
                        <i class="ni ni-menu"></i>
                    </a>
                    <ul>
                        <li>
                            <a href="#" class="btn js-waves-off" data-action="toggle" data-class="nav-function-minify" title="Minificar navegacion">
                                <i class="ni ni-minify-nav"></i>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="btn js-waves-off" data-action="toggle" data-class="nav-function-fixed" title="Lock Navigation">
                                <i class="ni ni-lock-nav"></i>
                            </a>
                        </li>
                    </ul>
                </div>
                <!-- DOC: mobile button appears during mobile width -->
                <div class="hidden-lg-up">
                    <a href="#" class="header-btn btn press-scale-down" data-action="toggle" data-class="mobile-nav-on">
                        <i class="ni ni-menu"></i>
                    </a>
                </div>

                <div class="ml-auto d-flex">
                    <!-- activate app search icon (mobile) -->

                    <!-- app settings -->

                    <!-- app shortcuts -->
                    <div class="hidden-md-down">
                        <a href="javascript:void(0);" class="header-icon" data-action="toggle" data-class="d-flex" data-target="#panel-compose" >
                            <i class="fa fa-edit" style="font-weight: 100;"></i>
                        </a>
                    </div>
                    <!-- app message -->
                    <div class="hidden-md-down">
                        <a href="#" class="header-icon" data-toggle="modal" data-target=".calendar" >
                            <i class="fal fa-calendar"></i>
                        </a>
                    </div>
                    <!-- app notification -->
                    <div>
                        <a href="#" class="header-icon" data-toggle="dropdown" title="You got # notifications">
                            <i class="fal fa-bell"></i>
                            <span class="badge badge-icon">0</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-animated dropdown-xl">
                            <div class="dropdown-header bg-trans-gradient d-flex justify-content-center align-items-center rounded-top mb-2">
                                <h4 class="m-0 text-center color-white">

                                </h4>
                            </div>

                            <div class="tab-content tab-notification">
                                <div class="tab-pane active p-3 text-center">
                                    <h5 class="mt-4 pt-4 fw-500">

                                        <small class="mt-3 fs-b fw-400 text-muted">
                                            ¡No tienes notificaciones por ahora!

                                        </small>
                                    </h5>
                                </div>
                                <div class="tab-pane" id="tab-messages" role="tabpanel">
                                    <div class="custom-scroll h-100">

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <!-- app user menu -->
                    <div>
                        <a href="#" data-toggle="dropdown" title="{{$user->email}}" class="header-icon d-flex align-items-center justify-content-center ml-2">
                            <img src="/storage/profile/{{$user->profile}}" class="profile-image rounded-circle" alt="{{$user->name}}">
                            <!-- you can also add username next to the avatar with the codes below:
                            <span class="ml-1 mr-1 text-truncate text-truncate-header hidden-xs-down">Me</span>
                            <i class="ni ni-chevron-down hidden-xs-down"></i> -->
                        </a>
                        <div class="dropdown-menu dropdown-menu-animated dropdown-lg">
                            <div class="dropdown-header bg-trans-gradient d-flex flex-row py-4 rounded-top">
                                <div class="d-flex flex-row align-items-center mt-1 mb-1 color-white">
                                    <span class="mr-2">
                                        <img src="/storage/profile/{{$user->profile}}" class="rounded-circle profile-image" alt="{{$user->name}}">
                                    </span>
                                    <div class="info-card-text">
                                        <div class="fs-lg text-truncate text-truncate-lg">{{$user->name}}</div>
                                        <span class="text-truncate text-truncate-md opacity-80">{{$user->email}}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="dropdown-divider m-0"></div>
                            <div class="dropdown-divider m-0"></div>
                            <a href="#" class="dropdown-item" data-action="app-fullscreen">
                                <span data-i18n="drpdwn.fullscreen">Pantalla Completa</span>
                                <i class="float-right text-muted fw-n">F11</i>
                            </a>
                            <div class="dropdown-divider m-0"></div>
                            <a class="dropdown-item fw-500 pt-3 pb-3" href="/logout">
                                <span data-i18n="drpdwn.page-logout">Cerrar Sesion</span>

                            </a>
                        </div>
                    </div>
                </div>
            </header>
            <!-- END Page Header -->
            <!-- BEGIN Page Content -->
            <!-- the #js-page-content id is needed for some plugins to initialize -->
            <main id="js-page-content" role="main" class="page-content">
                @yield('content')

                <!-- Calendario -->
                @component("modal.calendar")

                @endcomponent
                <!-- End Calendario -->
            </main>
            <!-- this overlay is activated only when mobile menu is triggered -->
            <div class="page-content-overlay" data-action="toggle" data-class="mobile-nav-on"></div> <!-- END Page Content -->
            <!-- BEGIN Page Footer -->
            <footer class="page-footer" role="contentinfo">
                <div class="d-flex align-items-center flex-1 text-muted">
                    <span class="hidden-md-down fw-700">2019 © S.M.A.R.T por&nbsp;<a href='https://www.novoples.net' class='text-primary fw-500' title='novoples.net' target='_blank'>NovoPles</a></span>
                </div>
            </footer>
            <!-- END Page Footer -->

            <!-- Perfil -->
            @component("modal.profile",["user"=>$user])

            @endcomponent
            <!-- End Perfil -->
            <!-- Nuevo Correo -->
            @component("modal.compose",["users"=>$users])

            @endcomponent
            <!-- End Nuevo Correo -->
        </div>
    </div>
</div>
<!-- END Page Wrapper -->
<!-- BEGIN Quick Menu -->
<!-- to add more items, please make sure to change the variable '$menu-items: number;' in your _page-components-shortcut.scss -->
<nav class="shortcut-menu d-none d-sm-block">
    <input type="checkbox" class="menu-open" name="menu-open" id="menu_open" />
    <label for="menu_open" class="menu-open-button ">
        <span class="app-shortcut-icon d-block"></span>
    </label>
    <a href="#" class="menu-item btn" data-toggle="tooltip" data-placement="left" title="Ir hacia arriba">
        <i class="fal fa-arrow-up"></i>
    </a>
    <a href="/logout" class="menu-item btn" data-toggle="tooltip" data-placement="left" title="Cerrar Sesion">
        <i class="fal fa-sign-out"></i>
    </a>
    <a href="#" class="menu-item btn" data-action="app-fullscreen" data-toggle="tooltip" data-placement="left" title="Pantalla Completa">
        <i class="fal fa-expand"></i>
    </a>
</nav>
<!-- END Quick Menu -->
<!-- BEGIN Messenger -->

<!-- BEGIN Page Settings -->

<!-- base vendor bundle:
     DOC: if you remove pace.js from core please note on Internet Explorer some CSS animations may execute before a page is fully loaded, resulting 'jump' animations
                + pace.js (recommended)
                + jquery.js (core)
                + jquery-ui-cust.js (core)
                + popper.js (core)
                + bootstrap.js (core)
                + slimscroll.js (extension)
                + app.navigation.js (core)
                + ba-throttle-debounce.js (core)
                + waves.js (extension)
                + smartpanels.js (extension)
                + src/../jquery-snippets.js (core) -->
<script src="/js/vendors.bundle.js"></script>
<script src="/js/app.bundle.js"></script>
<script src="/js/dependency/moment/moment.js"></script>
<script src="/js/miscellaneous/fullcalendar/fullcalendar.bundle.js"></script>
<script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
<script src="/js/formplugins/select2/select2.bundle.js"></script>
<script>

    $(document).ready(function(){
        $('.select2').select2();

        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl,
            {
                plugins: ['dayGrid', 'bootstrap'],
                themeSystem: 'bootstrap',
                timeZone: 'UTC',
                dateAlignment: "month", //week, month
                buttonText:
                    {
                        today: 'today',
                        month: 'month',
                        week: 'week',
                        day: 'day',
                        list: 'list'
                    },
                eventTimeFormat:
                    {
                        hour: 'numeric',
                        minute: '2-digit',
                        meridiem: 'short'
                    },
                navLinks: true,
                header:
                    {
                        left: 'title',
                        center: '',
                        right: 'today prev,next'
                    },
                footer:
                    {
                        left: 'dayGridMonth',
                        center: '',
                        right: ''
                    },
                editable: true,
                eventLimit: true, // allow "more" link when too many events
                events: [
                    @foreach($events as $event)
                    {
                        title: "{{$event->descripcion}}",
                        start: "{{$event->fecha}}",
                        className: "bg-white border-primary text-primary"
                    },

                    @endforeach
                ],
                viewSkeletonRender: function()
                {
                    $('.fc-toolbar .btn-default').addClass('btn-sm');
                    $('.fc-header-toolbar h2').addClass('fs-md');
                    $('#calendar').addClass('fc-reset-order')
                },

            });

        calendar.render();
        $('.calendar').on('shown.bs.modal', function (e) {
            $(".fc-dayGridMonth-button").click();
        });
        $(".calendar-event-date").datepicker({dateFormat:"dd/mm/yy"});

    });
</script>
<!--<script src="js/../script.js"></script>
<script>
$(document).ready(function () {

});
</script>-->
</body>
</html>
