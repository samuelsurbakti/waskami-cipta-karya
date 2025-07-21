<!DOCTYPE html>
<html lang="en" class=" layout-navbar-fixed layout-menu-fixed layout-compact " dir="ltr" data-skin="default" data-assets-path="/themes/" data-template="horizontal-menu-template" data-bs-theme="light">
	<head>
		@include('ui.layouts.parts.head')
	</head>
	<body>
		<div class="layout-wrapper layout-navbar-full layout-horizontal layout-without-menu">
			<div class="layout-container">
				@include('ui.layouts.parts.navbar')

                <div class="layout-page">
					<div class="content-wrapper">
						@include('ui.layouts.horizontal.menu')

                        <div class="container-xxl flex-grow-1 container-p-y">
							{{ $slot }}
						</div>

                        @include('ui.layouts.parts.footer')
					</div>
				</div>
			</div>
		</div>

        @include('ui.layouts.parts.scripts')
        <script>
            function reloadTemplateScripts() {
                // Script yang wajib di-reload (daftar dinamis)
                const scripts = Array.from(document.querySelectorAll('script[data-reload-on-navigate]'));
                const links = Array.from(document.querySelectorAll('link[data-reload-on-navigate]'));

                scripts.forEach(oldScript => {
                    const newScript = document.createElement('script');
                    newScript.src = oldScript.src;
                    newScript.async = false;
                    newScript.setAttribute('data-reload-on-navigate', '');
                    oldScript.remove();
                    document.body.appendChild(newScript);
                });

                links.forEach(oldLink => {
                    const newLink = document.createElement('link');
                    newLink.rel = "stylesheet";
                    newLink.href = oldLink.href;
                    newLink.async = false;
                    newLink.setAttribute('data-reload-on-navigate', '');
                    oldLink.remove();
                    document.head.appendChild(newLink);
                });
            }

            // Jalankan pertama kali
            document.addEventListener('DOMContentLoaded', reloadTemplateScripts);

            // Jalankan setelah Livewire navigasi
            document.addEventListener('livewire:navigated', reloadTemplateScripts);
        </script>
	</body>
</html>
