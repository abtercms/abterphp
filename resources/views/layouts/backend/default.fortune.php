<!doctype html>
<html lang="en">
<head>
    <meta name="viewport" content="initial-scale=1"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="$description">
    <meta content="width=device-width, initial-scale=1, user-scalable=no" name="viewport">

    {{! charset("utf-8") !}}
    {{! pageTitle($title) !}}

    <link rel="shortcut icon" type="image/png" href="/favicon.png">

    {{! $preHeader !}}
    {{! $header !}}
    {{! $postHeader !}}

    {{! assetCss('admin-layout') !}}
    <% if ($page) %>
    {{! assetCss($page) !}}
    <% endif %>

    {{! assetJs('admin-layout-header') !}}
    <% if ($pageHeader) %>
    {{! assetJs($pageHeader) !}}
    <% endif %>
</head>

<body>
    <!-- Header Starts -->
    <!--Start Nav bar -->
    <nav class="navbar navbar-inverse navbar-fixed-top pmd-navbar pmd-z-depth">
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <a href="javascript:void(0);"
                   class="btn btn-sm pmd-btn-fab pmd-btn-flat pmd-ripple-effect pull-left margin-r8 pmd-sidebar-toggle"><i
                            class="material-icons">menu</i></a>
                <a href="{{! route('dashboard') !}}" class="navbar-brand"></a>
            </div>
        </div>
    </nav><!--End Nav bar -->
    <!-- Header Ends -->

    <!-- Sidebar Starts -->
    <div class="pmd-sidebar-overlay"></div>

    <!-- Left sidebar -->
    <aside class="pmd-sidebar sidebar-default pmd-sidebar-slide-push pmd-sidebar-left pmd-sidebar-open bg-fill-darkblue sidebar-with-icons" role="navigation">
        {{! $navigation !}}
    </aside><!-- End Left sidebar -->
    <!-- Sidebar Ends -->

    <!--content area start-->
    <div id="content" class="pmd-content inner-page">
        <!--tab start-->
        <div class="container-fluid full-width-container">
            <main class="main">
                <% show("content") %>
            </main>

        </div><!-- tab end -->
    </div><!-- content area end -->

    <!-- Optional JavaScript -->
    {{! $preFooter !}}
    {{! $footer !}}
    {{! $postFooter !}}

    <!-- Scripts Starts -->
    {{! assetJs('admin-layout-footer') !}}
    <% if ($pageFooter) %>
    {{! assetJs($pageFooter) !}}
    <% endif %>
    <!-- Scripts Ends -->
</body>
</html>
