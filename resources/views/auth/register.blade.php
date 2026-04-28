@extends('layouts.auth')

@section('title', __('backend.auth_register.page_title'))
@section('body_class', 'register-page')

@section('auth_actions')
    <a href="{{ route('admin.login.show') }}" class="auth-link-btn">
        {{ __('backend.auth_register.login') }}
    </a>
@endsection

@push('auth_styles')
<link rel="stylesheet" type="text/css" href="{{ asset('src/plugins/jquery-steps/jquery.steps.css') }}" />
<style>
    .register-page-wrap {
        padding: 40px 0;
    }

    .register-box {
        overflow: hidden;
    }

    .register-intro {
        padding: 26px 28px 10px;
        border-bottom: 1px solid #eef2f7;
        text-align: center;
    }

    .register-title {
        margin: 0;
        font-size: 1.9rem;
        font-weight: 800;
        color: #1b00ff;
    }

    .register-subtitle {
        margin: 10px 0 0;
        color: #7b8497;
        font-size: 0.95rem;
        line-height: 1.7;
    }

    .wizard-content {
        padding: 10px 0 0;
    }

    .tab-wizard2 .steps {
        padding: 0 24px;
    }

    .tab-wizard2 .content {
        padding: 20px 24px 10px;
    }

    .tab-wizard2 .actions {
        padding: 0 24px 24px;
    }

    .register-info {
        list-style: none;
        margin: 0;
        padding: 0;
        border: 1px solid #eef2f7;
        border-radius: 12px;
        overflow: hidden;
    }

    .register-info li {
        padding: 14px 16px;
        border-bottom: 1px solid #eef2f7;
    }

    .register-info li:last-child {
        border-bottom: 0;
    }

    #testSubmit {
        min-width: 220px;
    }

    @media (max-width: 767px) {
        .register-intro {
            padding: 22px 18px 10px;
        }

        .tab-wizard2 .steps,
        .tab-wizard2 .content,
        .tab-wizard2 .actions {
            padding-left: 16px;
            padding-right: 16px;
        }

        .register-title {
            font-size: 1.55rem;
        }
    }
</style>
@endpush

@section('content')
<div class="register-page-wrap d-flex align-items-center flex-wrap justify-content-center">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6 col-lg-7">
                <img src="{{ asset('vendors/images/register-page-img.png') }}" alt="{{ __('backend.auth_register.register_image_alt') }}" />
            </div>

            <div class="col-md-6 col-lg-5">
                <div class="register-box bg-white box-shadow border-radius-10">
                    <div class="register-intro">
                        <h2 class="register-title">{{ __('backend.auth_register.heading') }}</h2>
                        <p class="register-subtitle">{{ __('backend.auth_register.subtitle') }}</p>
                    </div>

                    <div class="wizard-content">
                        <form id="registerForm"
                              class="tab-wizard2 wizard-circle wizard"
                              action="{{ route('admin.register.post') }}"
                              method="POST">
                            @csrf

                            <h5>{{ __('backend.auth_register.steps.basic_account_credentials') }}</h5>
                            <section>
                                <div class="form-wrap max-width-600 mx-auto">
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">{{ __('backend.auth_register.email_address_required') }}</label>
                                        <div class="col-sm-8">
                                            <input type="email" name="email" class="form-control" required />
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">{{ __('backend.auth_register.username_required') }}</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="username" class="form-control" required />
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">{{ __('backend.auth_register.password_required') }}</label>
                                        <div class="col-sm-8">
                                            <input type="password" name="password" class="form-control" required />
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">{{ __('backend.auth_register.confirm_password_required') }}</label>
                                        <div class="col-sm-8">
                                            <input type="password" name="password_confirmation" class="form-control" required />
                                        </div>
                                    </div>
                                </div>
                            </section>

                            <h5>{{ __('backend.auth_register.steps.personal_information') }}</h5>
                            <section>
                                <div class="form-wrap max-width-600 mx-auto">
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">{{ __('backend.auth_register.full_name') }}</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="full_name" class="form-control" required />
                                        </div>
                                    </div>

                                    <div class="form-group row align-items-center">
                                        <label class="col-sm-4 col-form-label">{{ __('backend.auth_register.gender') }}</label>
                                        <div class="col-sm-8">
                                            <div class="custom-control custom-radio custom-control-inline pb-0">
                                                <input type="radio" id="male" name="gender" value="male" class="custom-control-input" required />
                                                <label class="custom-control-label" for="male">{{ __('backend.auth_register.gender_male') }}</label>
                                            </div>

                                            <div class="custom-control custom-radio custom-control-inline pb-0">
                                                <input type="radio" id="female" name="gender" value="female" class="custom-control-input" required />
                                                <label class="custom-control-label" for="female">{{ __('backend.auth_register.gender_female') }}</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">{{ __('backend.auth_register.city') }}</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="city" class="form-control" />
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">{{ __('backend.auth_register.state') }}</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="state" class="form-control" />
                                        </div>
                                    </div>
                                </div>
                            </section>

                            <h5>{{ __('backend.auth_register.steps.overview_information') }}</h5>
                            <section>
                                <div class="form-wrap max-width-600 mx-auto">
                                    <ul class="register-info">
                                        <li>
                                            <div class="row">
                                                <div class="col-sm-4 weight-600">{{ __('backend.auth_register.overview.email_address') }}</div>
                                                <div class="col-sm-8" id="overview-email"></div>
                                            </div>
                                        </li>

                                        <li>
                                            <div class="row">
                                                <div class="col-sm-4 weight-600">{{ __('backend.auth_register.overview.username') }}</div>
                                                <div class="col-sm-8" id="overview-username"></div>
                                            </div>
                                        </li>

                                        <li>
                                            <div class="row">
                                                <div class="col-sm-4 weight-600">{{ __('backend.auth_register.overview.full_name') }}</div>
                                                <div class="col-sm-8" id="overview-fullname"></div>
                                            </div>
                                        </li>

                                        <li>
                                            <div class="row">
                                                <div class="col-sm-4 weight-600">{{ __('backend.auth_register.overview.location') }}</div>
                                                <div class="col-sm-8" id="overview-location"></div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </section>
                        </form>
                    </div>
                </div>

                <div class="text-center mt-3">
                    <button type="button" id="testSubmit" class="btn btn-primary btn-lg">
                        {{ __('backend.auth_register.register') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('auth_scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('src/plugins/jquery-steps/jquery.steps.js') }}"></script>
<script src="{{ asset('vendors/scripts/steps-setting.js') }}"></script>

<script>
$(document).ready(function () {
    $(".actions li a[href='#finish']").remove();

    let testBtnExists = false;

    $("#registerForm").on("stepChanging", function (event, currentIndex, newIndex) {
        let valid = true;

        if (currentIndex === 0) {
            $("#registerForm section").eq(currentIndex).find("input[required]").each(function () {
                if (!$(this).val()) {
                    valid = false;
                    $(this).addClass("is-invalid");
                } else {
                    $(this).removeClass("is-invalid");
                }
            });

            if (!valid) {
                alert(@json(__('backend.auth_register.complete_required_fields_alert')));
                return false;
            }
        }

        $("#testSubmitBtn").remove();
        testBtnExists = false;

        return true;
    });

    $("#registerForm").on("stepChanged", function (event, currentIndex) {
        var totalSteps = $("#registerForm").find("h5").length;

        if (currentIndex === totalSteps - 1 && !testBtnExists) {
            var prevBtn = $(".actions li a[href='#previous']");
            var registerBtn = $('<a href="javascript:void(0);" id="testSubmitBtn" class="btn btn-primary">{{ __('backend.auth_register.register') }}</a>');

            prevBtn.parent().css("display", "flex");
            prevBtn.after(registerBtn);

            registerBtn.css({
                "padding": prevBtn.css("padding"),
                "font-size": prevBtn.css("font-size"),
                "height": prevBtn.css("height"),
                "line-height": prevBtn.css("line-height"),
                "margin-inline-start": "10px"
            });

            registerBtn.on("click", function () {
                $("#testSubmit").click();
            });

            testBtnExists = true;
        }
    });

    $("#testSubmit").hide();

    function updateOverview() {
        $("#overview-email").text($("input[name='email']").val());
        $("#overview-username").text($("input[name='username']").val());
        $("#overview-fullname").text($("input[name='full_name']").val());

        var city = $("input[name='city']").val();
        var state = $("input[name='state']").val();
        var location = city;
        if (state) location += ", " + state;
        $("#overview-location").text(location);
    }

    $("input").on("input change", updateOverview);

    $.ajaxSetup({
        headers: { "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content") },
    });

    $("#testSubmit").on("click", function () {
        var formData = $("#registerForm").serializeArray();
        $("#registerForm input[type=radio]:checked").each(function () {
            formData.push({ name: this.name, value: this.value });
        });

        $.ajax({
            url: $("#registerForm").attr("action"),
            method: "POST",
            data: formData,
            dataType: "json",
            success: function (response) {
                if (response.success || response.message) {
                    alert("✅ " + (response.message || @json(__('backend.auth_register.register_success'))));

                    if (response.redirect_url) {
                        window.location.href = response.redirect_url;
                    } else {
                        window.location.href = "{{ route('manager.dashboard') }}";
                    }
                } else {
                    alert("❌ " + response.message);
                }
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    let msg = "";
                    $.each(errors, function (key, value) {
                        msg += "❌ " + value + "\n";
                    });
                    alert(msg);
                } else {
                    let msg = @json(__('backend.auth_register.unexpected_error')) + "\n";
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        msg += "Message: " + xhr.responseJSON.message + "\n";
                    }
                    if (xhr.responseJSON && xhr.responseJSON.trace) {
                        msg += "Trace:\n" + xhr.responseJSON.trace;
                    } else {
                        msg += "Response Text:\n" + xhr.responseText;
                    }
                    alert(msg);
                }
            },
        });
    });
});
</script>
@endpush