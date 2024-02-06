<!DOCTYPE html>
<html lang="en">
    <head>
        <!--META IN EVERY TEMPLATES-->
        @include('Templates.Includes.Meta.meta')
        <!--META IN AUTH TEMPLATE ONLY-->
        @include('Templates.Auth.Includes.Meta.meta')
        <!--TITLE-->
        @yield('title')
        <!--FONTS-->
        @include('Templates.Includes.Fonts.font')
        <!--CSS SOURCES IN EVERY TEMPLATES-->
        @include('Templates.Includes.Sources.css')
        <!--CSS SOURCES IN AUTH TEMPLATE ONLY-->
        @include('Templates.Auth.Includes.Sources.css')
    </head>
    <body class="d-flex p-5 align-items-center py-4 bg-body-tertiary">
        <!--SVG ICONS-->
        @include('Templates.Includes.SVGs.bootstrap')
        @include('Templates.Includes.SVGs.fontawesome')
        <!--HEADER-->
        @include('Templates.Auth.Includes.Layouts.header')
        <!--BODY / CONTENTS-->
        @yield('contents')
        <!--MODALS IN EVERY TEMPLATES-->
        @include('Templates.Includes.Components.modal')
        <!--MODALS IN AUTH TEMPLATE ONLY-->
        @include('Templates.Auth.Includes.Components.modal')
        <!--MODALS IN EACH PAGES-->
        @yield('modals')
        <!--FOOTER-->
        @include('Templates.Auth.Includes.Layouts.footer')
        <!--SCRIPTS (JS) IN EVERY TEMPLATES-->
        @include('Templates.Includes.Sources.js')
        <!--SCRIPTS (JS) IN AUTH TEMPLATE ONLY-->
        @include('Templates.Auth.Includes.Sources.js')
        <!--SCRIPTS (JS) IN EACH PAGES-->
        @stack('scripts')
    </body>
</html>
