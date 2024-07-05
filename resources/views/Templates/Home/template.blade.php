<!DOCTYPE html>
<html lang="en">
    <head>
        <!--META IN EVERY TEMPLATES-->
        @include('Templates.Includes.Meta.meta')
        <!--META IN HOME TEMPLATE ONLY-->
        @include('Templates.Home.Includes.Meta.meta')
        <!--TITLE-->
        @yield('title')
        <!--FONTS-->
        @include('Templates.Includes.Fonts.font')
        <!--CSS SOURCES IN EVERY TEMPLATES-->
        @include('Templates.Includes.Sources.css')
        <!--CSS SOURCES IN HOME TEMPLATE ONLY-->
        @include('Templates.Home.Includes.Sources.css')
    </head>
    <body>
        <!--SVG ICONS-->
        @include('Templates.Includes.SVGs.bootstrap')
        @include('Templates.Includes.SVGs.fontawesome')
        <!--HEADER-->
        @include('Templates.Home.Includes.Layouts.header')
        <!--BODY / CONTENTS-->
        <main>
            <div class="container">
                @yield('contents')
            </div>
        </main>
        <!--MODALS IN EVERY TEMPLATES-->
        @include('Templates.Includes.Components.modal')
        <!--MODALS IN HOME TEMPLATE ONLY-->
        @include('Templates.Home.Includes.Components.modal')
        <!--MODALS IN EACH PAGES-->
        @yield('modals')
        <!--TOASTS IN EVERY TEMPLATES-->
        @include('Templates.Includes.Components.toast')
        <!--TOASTS IN ADMIN TEMPLATE ONLY-->
        @include('Templates.Home.Includes.Components.toast')
        <!--TOASTS IN EACH PAGES-->
        @yield('toasts')
        <!--OFFCANVAS IN EVERY TEMPLATES-->
        @include('Templates.Includes.Components.offcanvas')
        <!--OFFCANVAS IN ADMIN TEMPLATE ONLY-->
        @include('Templates.Home.Includes.Components.offcanvas')
        <!--OFFCANVAS IN EACH PAGES-->
        @yield('offcanvas')
        <!--FOOTER-->
        @include('Templates.Home.Includes.Layouts.footer')
        <!--SCRIPTS (JS) IN EVERY TEMPLATES-->
        @include('Templates.Includes.Sources.js')
        <!--SCRIPTS (JS) IN HOME TEMPLATE ONLY-->
        @include('Templates.Home.Includes.Scripts.js')
        @include('Templates.Home.Includes.Sources.js')
        <!--SCRIPTS (JS) IN EACH PAGES-->
        @stack('scripts')
    </body>
</html>
