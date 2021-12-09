<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">

    <!-- The viewport meta tag helps setup a responsive mobile layout. -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= App::get('SiteName'); ?> - <?= App::get('PageTitle'); ?></title>
    <link rel="icon" type="image/png" href="/icon.png" />

    <!-- Bootstrap CSS. See also: 
    https://getbootstrap.com/docs/5.1/getting-started/introduction/ -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">

    <!-- Typography and icons help bring data to life. Here we include Google Fonts and Material icons to add vibes.-->
    <!-- Google Fonts: https://fonts.google.com/ --> 
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@300;500&display=swap" rel="stylesheet">
    <!-- Material Icons: https://fonts.google.com/icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- This stylesheet lives in our "public" folder -->
    <link rel="stylesheet" href="/styles.css">

    <!-- we will use VueJS and Axios to make smart file uploads -->
    <script src="https://unpkg.com/vue@next"></script>
    <script src="https://unpkg.com/axios@0.24.0/dist/axios.min.js"></script>

  </head>
  <body>

  <?php App::log('finished rendering header'); ?>