<!doctype html>
<html lang="en" class=" layout-wide  customizer-hide" dir="ltr" data-skin="default" data-assets-path="../../assets/" data-template="vertical-menu-template" data-bs-theme="light">
	<head>
        <meta charset="UTF-8">
        <title>Waskami Realty</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        @include('ui.partials.favicon')
        @include('ui.partials.default-font')
        @include('ui.partials.default-css')
        <link rel="stylesheet" href="/themes/vendor/css/pages/page-misc.css" />
        @stack('page_styles')
        @include('ui.partials.themescripts')
	</head>
	<body>
        <div class="container-xxl container-p-y">
			<div class="misc-wrapper">
				<h3 class="mb-2 mx-2">Sedang menyiapkan pondasi untuk sesuatu yang lebih besar! 🚧</h3>
				<p class="mb-6 mx-2">Situs web kami sedang dalam perbaikan untuk memberikan pengalaman yang lebih kokoh dan modern.</p>
				<div class="mt-6"><img src="/src/assets/illustrations/under-construction.svg" alt="Under Construction" width="500" class="img-fluid" /></div>
			</div>
		</div>

        @include('ui.layouts.parts.scripts')
	</body>
</html>
