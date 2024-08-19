<!DOCTYPE html>
<html lang="en">
    <head>
        <!--META IN EVERY TEMPLATES-->
        @include('Templates.Includes.Meta.meta')
        <!--META IN AUTH TEMPLATE ONLY-->
        @include('Templates.Errors.Includes.Meta.meta')
        <!--TITLE-->
        @yield('title')
        <!--FONTS-->
        @include('Templates.Includes.Fonts.font')
        <!--CSS SOURCES IN EVERY TEMPLATES-->
        @include('Templates.Includes.Sources.css')
        <!--CSS SOURCES IN AUTH TEMPLATE ONLY-->
        @include('Templates.Errors.Includes.Sources.css')
    </head>
    <body class="d-flex p-5 align-items-center py-4 bg-body-tertiary">
        <!--SVG ICONS-->
        @include('Templates.Includes.SVGs.bootstrap')
        @include('Templates.Includes.SVGs.fontawesome')
        <!--BODY / CONTENTS-->
        @yield('contents')
        <!--MODALS IN EVERY TEMPLATES-->
        @include('Templates.Includes.Components.modal')
        <!--OFFCANVAS IN EACH PAGES-->
        @yield('offcanvas')
        <!--SCRIPTS (JS) IN EVERY TEMPLATES-->
        @include('Templates.Includes.Sources.js')
        <!--SCRIPTS (JS) IN AUTH TEMPLATE ONLY-->
        @include('Templates.Errors.Includes.Sources.js')
    </body>
</html>
