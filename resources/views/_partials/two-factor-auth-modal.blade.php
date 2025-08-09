<div class="modal fade" tabindex="-1" id="two_factor_auth_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Two Factor Authentication</h3>

                <!--begin::Close-->
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
                <!--end::Close-->
            </div>

            <div class="modal-body row">
                <div class="col-md-6">
                    {{-- <div class="fv-row mb-3">
                        <div class="form-check form-check-custom form-check-solid">
                            <input class="form-check-input" type="radio" value="mobile" name="auth_method"/>
                            <label class="form-check-label" for="mobile">
                                {{ auth()->user()->mobile }}
                            </label>
                        </div>
                    </div> --}}
                    <div class="fv-row mb-3">
                        <div class="form-check form-check-custom form-check-solid">
                            <input class="form-check-input" type="radio" value="email" name="auth_method" checked/>
                            <label class="form-check-label" for="email">
                                {{ auth()->user()->email }}
                            </label>
                        </div>
                    </div>

                    <button type="button" class="btn btn-primary" id="send_code">
                        <span class="indicator-label">
                            Resend code
                        </span>
                        <span class="indicator-progress">
                            Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </span>
                    </button>
                </div>

                <div class="col-md-6">
                    <div class="input-group mb-5">
                        <span class="input-group-text" id="code">Code:</span>
                        <input type="text" placeholder="******" name="code" id="codeInput" maxlength="6" autocomplete="off" class="form-control bg-transparent" disabled/>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary disabled" id="save_button">Save</button>
            </div>
        </div>
    </div>
</div>