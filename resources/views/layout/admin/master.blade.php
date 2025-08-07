<!DOCTYPE html>
<html lang="en">
	
  @include('layout.admin.header.head')

    <body id="kt_app_body" data-kt-app-layout="dark-sidebar" data-kt-app-header-fixed="true" data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true" data-kt-app-sidebar-push-header="true" data-kt-app-sidebar-push-toolbar="true" data-kt-app-sidebar-push-footer="true" data-kt-app-toolbar-enabled="true" class="app-default">
		
      <div class="d-flex flex-column flex-root app-root" id="kt_app_root">

        <div class="app-page flex-column flex-column-fluid" id="kt_app_page">

          <div id="kt_app_header" class="app-header" data-kt-sticky="true" data-kt-sticky-activate="{default: true, lg: true}" data-kt-sticky-name="app-header-minimize" data-kt-sticky-offset="{default: '200px', lg: '0'}" data-kt-sticky-animation="false">

            <div class="app-container container-fluid d-flex align-items-stretch justify-content-between" id="kt_app_header_container">
              
              @include('layout.admin.mobile.layout')
            
              <div class="d-flex align-items-stretch justify-content-between flex-lg-grow-1" id="kt_app_header_wrapper">

                <div class="app-header-menu app-header-mobile-drawer align-items-stretch" data-kt-drawer="true" data-kt-drawer-name="app-header-menu" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="250px" data-kt-drawer-direction="end" data-kt-drawer-toggle="#kt_app_header_menu_toggle" data-kt-swapper="true" data-kt-swapper-mode="{default: 'append', lg: 'prepend'}" data-kt-swapper-parent="{default: '#kt_app_body', lg: '#kt_app_header_wrapper'}">
                  
                </div>

                @include('layout.admin.navbar.navbar')

              </div>

            </div>

				</div>

				<div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">

					@include('layout.admin.sidebar.sidebar')

					<div class="app-main flex-column flex-row-fluid" id="kt_app_main">

            <div class="d-flex flex-column flex-column-fluid">

              <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">

                <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">

                  <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">

                    <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">@yield('page-title','Dashboard')</h1>

                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">

                      <li class="breadcrumb-item text-muted">
												<a href="@yield('main-breadcrumb-link','/')" class="text-muted text-hover-primary">@yield('main-breadcrumb','Home')</a>
											</li>
											
											<li class="breadcrumb-item">
												<span class="bullet bg-gray-500 w-5px h-2px"></span>
											</li>

                      <li class="breadcrumb-item text-muted">@yield('sub-breadcrumb','Dashboards')</li>

                    </ul>

                  </div>

									@yield('toolbar-actions')

								</div>

              </div>

              <div id="kt_app_content" class="app-content flex-column-fluid">

                <div id="kt_app_content_container" class="app-container container-fluid">

                  <div class="row g-5 gx-xl-10 mb-5 mb-xl-10">

										@yield('content')

									</div>

								</div>

							</div>
              
						</div>

            @include('layout.admin.footer.footer')

					</div>
          
				</div>

			</div>
      
		</div>

		<div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
			<i class="ki-duotone ki-arrow-up">
				<span class="path1"></span>
				<span class="path2"></span>
			</i>
		</div>
		
    @include('layout.admin.scripts.scripts')

	</body>

</html>