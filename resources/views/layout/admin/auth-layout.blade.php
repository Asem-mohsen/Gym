<!DOCTYPE html>
<html lang="en">
	
  @include('layout.admin.header.head')
    
	<body id="kt_app_body" data-kt-app-layout="dark-sidebar" data-kt-app-header-fixed="true" data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true" data-kt-app-sidebar-push-header="true" data-kt-app-sidebar-push-toolbar="true" data-kt-app-sidebar-push-footer="true" data-kt-app-toolbar-enabled="true" class="app-default">
		
		<div class="d-flex flex-column flex-root" id="kt_app_root">
      
			<div class="d-flex flex-column flex-lg-row flex-column-fluid">

				<div class="d-flex flex-column flex-lg-row-fluid w-lg-50 p-10 order-2 order-lg-1">

					<div class="d-flex flex-center flex-column flex-lg-row-fluid">

						<div class="w-lg-500px p-10 w-400px">

							@yield('form')

						</div>

					</div>

				</div>

				<div class="d-flex flex-lg-row-fluid w-lg-50 bgi-size-cover bgi-position-center order-1 order-lg-2 bg-position-center" style="background-image: url({{ 
					(isset($brandingData) && isset($brandingData['branding']['media_urls']['register_page_image']) && request()->routeIs('auth.register*')) ? $brandingData['branding']['media_urls']['register_page_image'] : 
					((isset($brandingData) && isset($brandingData['branding']['media_urls']['login_page_image']) && request()->routeIs('auth.login*')) ? $brandingData['branding']['media_urls']['login_page_image'] : 
					asset('assets/admin/img/hero-1.jpg'))
				}})">

					<div class="d-flex justify-content-between py-7 py-lg-15 px-5 px-md-15 w-100">

						<div class="w-50">
							<h3 class="d-none d-lg-block text-white fs-2qx fw-bolder text-start mb-7">
								{{ $brandingData['branding']['page_texts']['auth_common']['platform_title'] ?? 'Unlock your full potential.' }}
							</h3>
						
							<div class="d-none d-lg-block text-white fs-base text-start">
								<p>
									{{ $brandingData['branding']['page_texts']['auth_common']['platform_description'] ?? 'Stay consistent, stay strong — your fitness journey starts here' }}
								</p>
							</div>
						</div>

						<div class="w-50 text-end">
							<a href="{{ route('user.home' , ['siteSetting' => $siteSetting->slug]) }}" class="mb-0 mb-lg-12">
								@if(isset($siteSetting) && $siteSetting->getFirstMediaUrl('gym_logo') != null)
									<img src="{{ $siteSetting->getFirstMediaUrl('gym_logo') }}" alt="{{ $siteSetting->gym_name }}" class="h-60px h-lg-75px" />
								@else
									<img alt="Logo" src="{{ asset('assets/admin/img/logo.png') }}" class="h-60px h-lg-75px" />
								@endif
							</a>
						</div>

					</div>

				</div>

			</div>

		</div>
		
		@include('layout.admin.scripts.scripts')
		
		@include('components.gym-context-handler')

	</body>

</html>