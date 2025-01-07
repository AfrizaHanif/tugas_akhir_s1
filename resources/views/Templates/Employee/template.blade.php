<!DOCTYPE html>
<html lang="en">
    <head>
        <!--META IN EVERY TEMPLATES-->
        @include('Templates.Includes.Meta.meta')
        <!--META IN AUTH TEMPLATE ONLY-->
        @include('Templates.Employee.Includes.Meta.meta')
        <!--TITLE-->
        @yield('title')
        <!--FONTS-->
        @include('Templates.Includes.Fonts.font')
        <!--CSS SOURCES IN EVERY TEMPLATES-->
        @include('Templates.Includes.Sources.css')
        <!--CSS SOURCES IN EMPLOYEE TEMPLATE ONLY-->
        @include('Templates.Employee.Includes.Sources.css')
    </head>
    <body>
        <!--SVG ICONS-->
        @include('Templates.Includes.SVGs.bootstrap')
        @include('Templates.Includes.SVGs.fontawesome')
        <!--NO HEADER-->
        <!--BODY-->
        <div class="container-fluid overflow-hidden">
            <div class="row vh-100 overflow-auto">
                <!--SIDEBAR--> <!--KHUSUS BOOTSTRAP EXAMPLE: TAG <MAIN> DIHAPUS DAN GUNAKAN SETELAHNYA-->
                @include('Templates.Employee.Includes.Layouts.sidebar')
                <div class="col d-flex flex-column h-sm-100">
                    <!--CONTENTS-->
                    <main class="row overflow-auto">
                        <div class="col pt-4">
                            <div class="container">
                                @yield('contents')
                            </div>
                        </div>
                    </main>
                    <!--FOOTER-->
                    @include('Templates.Employee.Includes.Layouts.footer')
                </div>
            </div>
        </div>
        <!--MODALS IN EVERY TEMPLATES-->
        @include('Templates.Includes.Components.modal')
        <!--MODALS IN EMPLOYEE TEMPLATE ONLY-->
        @include('Templates.Employee.Includes.Components.modal')
        <!--MODALS IN EACH PAGES-->
        @yield('modals')
        <!--OFFCANVAS IN EVERY TEMPLATES-->
        @include('Templates.Includes.Components.offcanvas')
        <!--OFFCANVAS IN EMPLOYEE TEMPLATE ONLY-->
        @include('Templates.Employee.Includes.Components.offcanvas')
        <!--OFFCANVAS IN EACH PAGES-->
        @yield('offcanvas')
        <!--SCRIPTS (JS) IN EVERY TEMPLATES-->
        @include('Templates.Includes.Sources.js')
        <!--SCRIPTS (JS) IN EMPLOYEE TEMPLATE ONLY-->
        @include('Templates.Employee.Includes.Sources.js')
        <!--SCRIPTS (JS) IN EACH PAGES-->
        @stack('scripts')
    </body>
</html>
