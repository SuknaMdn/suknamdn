<div>
    <div class="card mb-5 mb-xl-10">
        <div class="card-body pt-9 pb-4">
            <!--begin::Details-->
            <div class="d-flex flex-wrap flex-sm-nowrap">
                <div class="flex-grow-1">
                    <!--begin::Title-->
                    <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                        <!--begin::User-->
                        <div class="d-flex flex-column">
                            <!--begin::Name-->
                            <div class="d-flex align-items-center mb-2">
                                <a href="#" class="text-gray-900 text-hover-primary fs-2 fw-bold me-1">{{ $authUser->name }}</a>
                                {{-- <a href="#">
                                    <i class="ki-outline ki-verify fs-1 text-primary"></i>
                                </a> --}}
                            </div>
                            <!--end::Name-->
                            <!--begin::Info-->
                            <div class="d-flex flex-wrap fw-semibold fs-6 mb-4 pe-2">
                                <a href="#" class="d-flex align-items-center text-gray-500 text-hover-primary me-5 mb-2">
                                <i class="ki-outline ki-shield fs-4 me-1"></i> {{ $authUser->isDeveloper() ? 'Developer' : 'Client' }}</a>
                                <a href="#" class="d-flex align-items-center text-gray-500 text-hover-primary me-5 mb-2">
                                <i class="ki-outline ki-geolocation fs-4 me-1"></i>{{ $authUser->country_code }}</a>
                                <a href="#" class="d-flex align-items-center text-gray-500 text-hover-primary mb-2">
                                    <i class="ki-outline ki-sms fs-4 me-1"></i>
                                    {{ $authUser->email }}
                                    <i class="ki-outline {{ $authUser->hasVerifiedEmail() ? 'ki-verify text-success' : 'ki-shield-cross text-danger' }} fs-4 ms-1 " data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $authUser->hasVerifiedEmail() ? 'Email Verified' : 'Email Not Verified' }}"></i>
                                </a>
                            </div>
                            <!--end::Info-->
                        </div>
                        <!--end::User-->
                        <!--begin::Actions-->
                        <div class="d-flex my-4">
                            {{-- <a href="{{ route('developer.profile') }}" class="btn btn-sm btn-primary me-3">Show Profile</a> --}}
                        </div>
                        <!--end::Actions-->

                    </div>
                </div>
                <!--end::Info-->
            </div>
            {{-- alert --}}
            {{-- <div class="notice d-flex bg-light-warning rounded border-warning border border-dashed p-6 my-4">
                <i class="ki-outline ki-information fs-2tx text-warning me-4"></i>
                <div class="d-flex flex-stack flex-grow-1">
                    <div class="fw-semibold">
                        <h4 class="text-gray-900 fw-bold">We need your attention!</h4>
                        <div class="fs-6 text-gray-700">Your payment was declined. To start using tools, please
                        <a class="fw-bold" href="account/billing.html">Add Payment Method</a>.</div>
                    </div>
                </div>
            </div> --}}
        </div>
    </div>

    <!--begin::Basic info-->
    <div class="card mb-5 mb-xl-10">
        <!--begin::Card header-->
        <div class="card-header border-0">
            <!--begin::Card title-->
            <div class="card-title m-0">
                <h3 class="fw-bold m-0">Profile Details</h3>
            </div>
            <!--end::Card title-->
        </div>
        <!--begin::Card header-->
        <!--begin::Content-->
        <div id="kt_account_settings_profile_details">
            <!--begin::Form-->
            <form class="form" wire:submit="updateUserProfile">
                <!--begin::Card body-->
                <div class="card-body border-top p-9">
                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label required fw-semibold fs-6">Full Name</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-lg-8">
                            <!--begin::Row-->
                            <div class="row">
                                <!--begin::Col-->
                                <div class="col-lg-6 fv-row">
                                    <input type="text" wire:model="firstname" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="First name" value="{{ $authUser->firstname }}" />
                                    @error('firstname')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <!--end::Col-->
                                <!--begin::Col-->
                                <div class="col-lg-6 fv-row">
                                    <input type="text" wire:model="lastname" class="form-control form-control-lg form-control-solid" placeholder="Last name" value="{{ $authUser->lastname }}" />
                                    @error('lastname')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Row-->
                        </div>
                        <!--end::Col-->
                    </div>

                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label fw-semibold fs-6">
                            <span class="required">Contact Phone</span>
                            <span class="ms-1" data-bs-toggle="tooltip" title="Phone number must be active">
                                <i class="ki-outline ki-information-5 text-gray-500 fs-6"></i>
                            </span>
                        </label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-lg-8 fv-row">
                            <input type="tel" wire:model="phone" class="form-control form-control-lg form-control-solid" placeholder="Phone number" value="{{ $authUser->phone }}" />
                            @error('phone')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <!--end::Col-->
                    </div>

                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label fw-semibold fs-6">
                            <span class="required">Country</span>
                            <span class="ms-1" data-bs-toggle="tooltip" title="Country of origination">
                                <i class="ki-outline ki-information-5 text-gray-500 fs-6"></i>
                            </span>
                        </label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-lg-8 fv-row">
                            <select wire:model="country_code" aria-label="Select a Country" data-control="select2" data-placeholder="Select a country..." class="form-select form-select-solid form-select-lg fw-semibold">
                                <option value="">Select a Country...</option>
                                <option data-kt-flag="flags/afghanistan.svg" value="AF">Afghanistan</option>
                                <option data-kt-flag="flags/aland-islands.svg" value="AX">Aland Islands</option>
                                <option data-kt-flag="flags/albania.svg" value="AL">Albania</option>
                                <option data-kt-flag="flags/algeria.svg" value="DZ">Algeria</option>
                                <option data-kt-flag="flags/american-samoa.svg" value="AS">American Samoa</option>
                                <option data-kt-flag="flags/andorra.svg" value="AD">Andorra</option>
                                <option data-kt-flag="flags/angola.svg" value="AO">Angola</option>
                                <option data-kt-flag="flags/anguilla.svg" value="AI">Anguilla</option>
                                <option data-kt-flag="flags/antigua-and-barbuda.svg" value="AG">Antigua and Barbuda</option>
                                <option data-kt-flag="flags/argentina.svg" value="AR">Argentina</option>
                                <option data-kt-flag="flags/armenia.svg" value="AM">Armenia</option>
                                <option data-kt-flag="flags/aruba.svg" value="AW">Aruba</option>
                                <option data-kt-flag="flags/australia.svg" value="AU">Australia</option>
                                <option data-kt-flag="flags/austria.svg" value="AT">Austria</option>
                                <option data-kt-flag="flags/azerbaijan.svg" value="AZ">Azerbaijan</option>
                                <option data-kt-flag="flags/bahamas.svg" value="BS">Bahamas</option>
                                <option data-kt-flag="flags/bahrain.svg" value="BH">Bahrain</option>
                                <option data-kt-flag="flags/bangladesh.svg" value="BD">Bangladesh</option>
                                <option data-kt-flag="flags/barbados.svg" value="BB">Barbados</option>
                                <option data-kt-flag="flags/belarus.svg" value="BY">Belarus</option>
                                <option data-kt-flag="flags/belgium.svg" value="BE">Belgium</option>
                                <option data-kt-flag="flags/belize.svg" value="BZ">Belize</option>
                                <option data-kt-flag="flags/benin.svg" value="BJ">Benin</option>
                                <option data-kt-flag="flags/bermuda.svg" value="BM">Bermuda</option>
                                <option data-kt-flag="flags/bhutan.svg" value="BT">Bhutan</option>
                                <option data-kt-flag="flags/bolivia.svg" value="BO">Bolivia, Plurinational State of</option>
                                <option data-kt-flag="flags/bonaire.svg" value="BQ">Bonaire, Sint Eustatius and Saba</option>
                                <option data-kt-flag="flags/bosnia-and-herzegovina.svg" value="BA">Bosnia and Herzegovina</option>
                                <option data-kt-flag="flags/botswana.svg" value="BW">Botswana</option>
                                <option data-kt-flag="flags/brazil.svg" value="BR">Brazil</option>
                                <option data-kt-flag="flags/british-indian-ocean-territory.svg" value="IO">British Indian Ocean Territory</option>
                                <option data-kt-flag="flags/brunei.svg" value="BN">Brunei Darussalam</option>
                                <option data-kt-flag="flags/bulgaria.svg" value="BG">Bulgaria</option>
                                <option data-kt-flag="flags/burkina-faso.svg" value="BF">Burkina Faso</option>
                                <option data-kt-flag="flags/burundi.svg" value="BI">Burundi</option>
                                <option data-kt-flag="flags/cambodia.svg" value="KH">Cambodia</option>
                                <option data-kt-flag="flags/cameroon.svg" value="CM">Cameroon</option>
                                <option data-kt-flag="flags/canada.svg" value="CA">Canada</option>
                                <option data-kt-flag="flags/cape-verde.svg" value="CV">Cape Verde</option>
                                <option data-kt-flag="flags/cayman-islands.svg" value="KY">Cayman Islands</option>
                                <option data-kt-flag="flags/central-african-republic.svg" value="CF">Central African Republic</option>
                                <option data-kt-flag="flags/chad.svg" value="TD">Chad</option>
                                <option data-kt-flag="flags/chile.svg" value="CL">Chile</option>
                                <option data-kt-flag="flags/china.svg" value="CN">China</option>
                                <option data-kt-flag="flags/christmas-island.svg" value="CX">Christmas Island</option>
                                <option data-kt-flag="flags/cocos-island.svg" value="CC">Cocos (Keeling) Islands</option>
                                <option data-kt-flag="flags/colombia.svg" value="CO">Colombia</option>
                                <option data-kt-flag="flags/comoros.svg" value="KM">Comoros</option>
                                <option data-kt-flag="flags/cook-islands.svg" value="CK">Cook Islands</option>
                                <option data-kt-flag="flags/costa-rica.svg" value="CR">Costa Rica</option>
                                <option data-kt-flag="flags/ivory-coast.svg" value="CI">Côte d'Ivoire</option>
                                <option data-kt-flag="flags/croatia.svg" value="HR">Croatia</option>
                                <option data-kt-flag="flags/cuba.svg" value="CU">Cuba</option>
                                <option data-kt-flag="flags/curacao.svg" value="CW">Curaçao</option>
                                <option data-kt-flag="flags/czech-republic.svg" value="CZ">Czech Republic</option>
                                <option data-kt-flag="flags/denmark.svg" value="DK">Denmark</option>
                                <option data-kt-flag="flags/djibouti.svg" value="DJ">Djibouti</option>
                                <option data-kt-flag="flags/dominica.svg" value="DM">Dominica</option>
                                <option data-kt-flag="flags/dominican-republic.svg" value="DO">Dominican Republic</option>
                                <option data-kt-flag="flags/ecuador.svg" value="EC">Ecuador</option>
                                <option data-kt-flag="flags/egypt.svg" value="EG">Egypt</option>
                                <option data-kt-flag="flags/el-salvador.svg" value="SV">El Salvador</option>
                                <option data-kt-flag="flags/equatorial-guinea.svg" value="GQ">Equatorial Guinea</option>
                                <option data-kt-flag="flags/eritrea.svg" value="ER">Eritrea</option>
                                <option data-kt-flag="flags/estonia.svg" value="EE">Estonia</option>
                                <option data-kt-flag="flags/ethiopia.svg" value="ET">Ethiopia</option>
                                <option data-kt-flag="flags/falkland-islands.svg" value="FK">Falkland Islands (Malvinas)</option>
                                <option data-kt-flag="flags/fiji.svg" value="FJ">Fiji</option>
                                <option data-kt-flag="flags/finland.svg" value="FI">Finland</option>
                                <option data-kt-flag="flags/france.svg" value="FR">France</option>
                                <option data-kt-flag="flags/french-polynesia.svg" value="PF">French Polynesia</option>
                                <option data-kt-flag="flags/gabon.svg" value="GA">Gabon</option>
                                <option data-kt-flag="flags/gambia.svg" value="GM">Gambia</option>
                                <option data-kt-flag="flags/georgia.svg" value="GE">Georgia</option>
                                <option data-kt-flag="flags/germany.svg" value="DE">Germany</option>
                                <option data-kt-flag="flags/ghana.svg" value="GH">Ghana</option>
                                <option data-kt-flag="flags/gibraltar.svg" value="GI">Gibraltar</option>
                                <option data-kt-flag="flags/greece.svg" value="GR">Greece</option>
                                <option data-kt-flag="flags/greenland.svg" value="GL">Greenland</option>
                                <option data-kt-flag="flags/grenada.svg" value="GD">Grenada</option>
                                <option data-kt-flag="flags/guam.svg" value="GU">Guam</option>
                                <option data-kt-flag="flags/guatemala.svg" value="GT">Guatemala</option>
                                <option data-kt-flag="flags/guernsey.svg" value="GG">Guernsey</option>
                                <option data-kt-flag="flags/guinea.svg" value="GN">Guinea</option>
                                <option data-kt-flag="flags/guinea-bissau.svg" value="GW">Guinea-Bissau</option>
                                <option data-kt-flag="flags/haiti.svg" value="HT">Haiti</option>
                                <option data-kt-flag="flags/vatican-city.svg" value="VA">Holy See (Vatican City State)</option>
                                <option data-kt-flag="flags/honduras.svg" value="HN">Honduras</option>
                                <option data-kt-flag="flags/hong-kong.svg" value="HK">Hong Kong</option>
                                <option data-kt-flag="flags/hungary.svg" value="HU">Hungary</option>
                                <option data-kt-flag="flags/iceland.svg" value="IS">Iceland</option>
                                <option data-kt-flag="flags/india.svg" value="IN">India</option>
                                <option data-kt-flag="flags/indonesia.svg" value="ID">Indonesia</option>
                                <option data-kt-flag="flags/iran.svg" value="IR">Iran, Islamic Republic of</option>
                                <option data-kt-flag="flags/iraq.svg" value="IQ">Iraq</option>
                                <option data-kt-flag="flags/ireland.svg" value="IE">Ireland</option>
                                <option data-kt-flag="flags/isle-of-man.svg" value="IM">Isle of Man</option>
                                <option data-kt-flag="flags/israel.svg" value="IL">Israel</option>
                                <option data-kt-flag="flags/italy.svg" value="IT">Italy</option>
                                <option data-kt-flag="flags/jamaica.svg" value="JM">Jamaica</option>
                                <option data-kt-flag="flags/japan.svg" value="JP">Japan</option>
                                <option data-kt-flag="flags/jersey.svg" value="JE">Jersey</option>
                                <option data-kt-flag="flags/jordan.svg" value="JO">Jordan</option>
                                <option data-kt-flag="flags/kazakhstan.svg" value="KZ">Kazakhstan</option>
                                <option data-kt-flag="flags/kenya.svg" value="KE">Kenya</option>
                                <option data-kt-flag="flags/kiribati.svg" value="KI">Kiribati</option>
                                <option data-kt-flag="flags/north-korea.svg" value="KP">Korea, Democratic People's Republic of</option>
                                <option data-kt-flag="flags/kuwait.svg" value="KW">Kuwait</option>
                                <option data-kt-flag="flags/kyrgyzstan.svg" value="KG">Kyrgyzstan</option>
                                <option data-kt-flag="flags/laos.svg" value="LA">Lao People's Democratic Republic</option>
                                <option data-kt-flag="flags/latvia.svg" value="LV">Latvia</option>
                                <option data-kt-flag="flags/lebanon.svg" value="LB">Lebanon</option>
                                <option data-kt-flag="flags/lesotho.svg" value="LS">Lesotho</option>
                                <option data-kt-flag="flags/liberia.svg" value="LR">Liberia</option>
                                <option data-kt-flag="flags/libya.svg" value="LY">Libya</option>
                                <option data-kt-flag="flags/liechtenstein.svg" value="LI">Liechtenstein</option>
                                <option data-kt-flag="flags/lithuania.svg" value="LT">Lithuania</option>
                                <option data-kt-flag="flags/luxembourg.svg" value="LU">Luxembourg</option>
                                <option data-kt-flag="flags/macao.svg" value="MO">Macao</option>
                                <option data-kt-flag="flags/madagascar.svg" value="MG">Madagascar</option>
                                <option data-kt-flag="flags/malawi.svg" value="MW">Malawi</option>
                                <option data-kt-flag="flags/malaysia.svg" value="MY">Malaysia</option>
                                <option data-kt-flag="flags/maldives.svg" value="MV">Maldives</option>
                                <option data-kt-flag="flags/mali.svg" value="ML">Mali</option>
                                <option data-kt-flag="flags/malta.svg" value="MT">Malta</option>
                                <option data-kt-flag="flags/marshall-island.svg" value="MH">Marshall Islands</option>
                                <option data-kt-flag="flags/martinique.svg" value="MQ">Martinique</option>
                                <option data-kt-flag="flags/mauritania.svg" value="MR">Mauritania</option>
                                <option data-kt-flag="flags/mauritius.svg" value="MU">Mauritius</option>
                                <option data-kt-flag="flags/mexico.svg" value="MX">Mexico</option>
                                <option data-kt-flag="flags/micronesia.svg" value="FM">Micronesia, Federated States of</option>
                                <option data-kt-flag="flags/moldova.svg" value="MD">Moldova, Republic of</option>
                                <option data-kt-flag="flags/monaco.svg" value="MC">Monaco</option>
                                <option data-kt-flag="flags/mongolia.svg" value="MN">Mongolia</option>
                                <option data-kt-flag="flags/montenegro.svg" value="ME">Montenegro</option>
                                <option data-kt-flag="flags/montserrat.svg" value="MS">Montserrat</option>
                                <option data-kt-flag="flags/morocco.svg" value="MA">Morocco</option>
                                <option data-kt-flag="flags/mozambique.svg" value="MZ">Mozambique</option>
                                <option data-kt-flag="flags/myanmar.svg" value="MM">Myanmar</option>
                                <option data-kt-flag="flags/namibia.svg" value="NA">Namibia</option>
                                <option data-kt-flag="flags/nauru.svg" value="NR">Nauru</option>
                                <option data-kt-flag="flags/nepal.svg" value="NP">Nepal</option>
                                <option data-kt-flag="flags/netherlands.svg" value="NL">Netherlands</option>
                                <option data-kt-flag="flags/new-zealand.svg" value="NZ">New Zealand</option>
                                <option data-kt-flag="flags/nicaragua.svg" value="NI">Nicaragua</option>
                                <option data-kt-flag="flags/niger.svg" value="NE">Niger</option>
                                <option data-kt-flag="flags/nigeria.svg" value="NG">Nigeria</option>
                                <option data-kt-flag="flags/niue.svg" value="NU">Niue</option>
                                <option data-kt-flag="flags/norfolk-island.svg" value="NF">Norfolk Island</option>
                                <option data-kt-flag="flags/northern-mariana-islands.svg" value="MP">Northern Mariana Islands</option>
                                <option data-kt-flag="flags/norway.svg" value="NO">Norway</option>
                                <option data-kt-flag="flags/oman.svg" value="OM">Oman</option>
                                <option data-kt-flag="flags/pakistan.svg" value="PK">Pakistan</option>
                                <option data-kt-flag="flags/palau.svg" value="PW">Palau</option>
                                <option data-kt-flag="flags/palestine.svg" value="PS">Palestinian Territory, Occupied</option>
                                <option data-kt-flag="flags/panama.svg" value="PA">Panama</option>
                                <option data-kt-flag="flags/papua-new-guinea.svg" value="PG">Papua New Guinea</option>
                                <option data-kt-flag="flags/paraguay.svg" value="PY">Paraguay</option>
                                <option data-kt-flag="flags/peru.svg" value="PE">Peru</option>
                                <option data-kt-flag="flags/philippines.svg" value="PH">Philippines</option>
                                <option data-kt-flag="flags/poland.svg" value="PL">Poland</option>
                                <option data-kt-flag="flags/portugal.svg" value="PT">Portugal</option>
                                <option data-kt-flag="flags/puerto-rico.svg" value="PR">Puerto Rico</option>
                                <option data-kt-flag="flags/qatar.svg" value="QA">Qatar</option>
                                <option data-kt-flag="flags/romania.svg" value="RO">Romania</option>
                                <option data-kt-flag="flags/russia.svg" value="RU">Russian Federation</option>
                                <option data-kt-flag="flags/rwanda.svg" value="RW">Rwanda</option>
                                <option data-kt-flag="flags/st-barts.svg" value="BL">Saint Barthélemy</option>
                                <option data-kt-flag="flags/saint-kitts-and-nevis.svg" value="KN">Saint Kitts and Nevis</option>
                                <option data-kt-flag="flags/st-lucia.svg" value="LC">Saint Lucia</option>
                                <option data-kt-flag="flags/sint-maarten.svg" value="MF">Saint Martin (French part)</option>
                                <option data-kt-flag="flags/st-vincent-and-the-grenadines.svg" value="VC">Saint Vincent and the Grenadines</option>
                                <option data-kt-flag="flags/samoa.svg" value="WS">Samoa</option>
                                <option data-kt-flag="flags/san-marino.svg" value="SM">San Marino</option>
                                <option data-kt-flag="flags/sao-tome-and-prince.svg" value="ST">Sao Tome and Principe</option>
                                <option data-kt-flag="flags/saudi-arabia.svg" value="SA">Saudi Arabia</option>
                                <option data-kt-flag="flags/senegal.svg" value="SN">Senegal</option>
                                <option data-kt-flag="flags/serbia.svg" value="RS">Serbia</option>
                                <option data-kt-flag="flags/seychelles.svg" value="SC">Seychelles</option>
                                <option data-kt-flag="flags/sierra-leone.svg" value="SL">Sierra Leone</option>
                                <option data-kt-flag="flags/singapore.svg" value="SG">Singapore</option>
                                <option data-kt-flag="flags/sint-maarten.svg" value="SX">Sint Maarten (Dutch part)</option>
                                <option data-kt-flag="flags/slovakia.svg" value="SK">Slovakia</option>
                                <option data-kt-flag="flags/slovenia.svg" value="SI">Slovenia</option>
                                <option data-kt-flag="flags/solomon-islands.svg" value="SB">Solomon Islands</option>
                                <option data-kt-flag="flags/somalia.svg" value="SO">Somalia</option>
                                <option data-kt-flag="flags/south-africa.svg" value="ZA">South Africa</option>
                                <option data-kt-flag="flags/south-korea.svg" value="KR">South Korea</option>
                                <option data-kt-flag="flags/south-sudan.svg" value="SS">South Sudan</option>
                                <option data-kt-flag="flags/spain.svg" value="ES">Spain</option>
                                <option data-kt-flag="flags/sri-lanka.svg" value="LK">Sri Lanka</option>
                                <option data-kt-flag="flags/sudan.svg" value="SD">Sudan</option>
                                <option data-kt-flag="flags/suriname.svg" value="SR">Suriname</option>
                                <option data-kt-flag="flags/swaziland.svg" value="SZ">Swaziland</option>
                                <option data-kt-flag="flags/sweden.svg" value="SE">Sweden</option>
                                <option data-kt-flag="flags/switzerland.svg" value="CH">Switzerland</option>
                                <option data-kt-flag="flags/syria.svg" value="SY">Syrian Arab Republic</option>
                                <option data-kt-flag="flags/taiwan.svg" value="TW">Taiwan, Province of China</option>
                                <option data-kt-flag="flags/tajikistan.svg" value="TJ">Tajikistan</option>
                                <option data-kt-flag="flags/tanzania.svg" value="TZ">Tanzania, United Republic of</option>
                                <option data-kt-flag="flags/thailand.svg" value="TH">Thailand</option>
                                <option data-kt-flag="flags/togo.svg" value="TG">Togo</option>
                                <option data-kt-flag="flags/tokelau.svg" value="TK">Tokelau</option>
                                <option data-kt-flag="flags/tonga.svg" value="TO">Tonga</option>
                                <option data-kt-flag="flags/trinidad-and-tobago.svg" value="TT">Trinidad and Tobago</option>
                                <option data-kt-flag="flags/tunisia.svg" value="TN">Tunisia</option>
                                <option data-kt-flag="flags/turkey.svg" value="TR">Turkey</option>
                                <option data-kt-flag="flags/turkmenistan.svg" value="TM">Turkmenistan</option>
                                <option data-kt-flag="flags/turks-and-caicos.svg" value="TC">Turks and Caicos Islands</option>
                                <option data-kt-flag="flags/tuvalu.svg" value="TV">Tuvalu</option>
                                <option data-kt-flag="flags/uganda.svg" value="UG">Uganda</option>
                                <option data-kt-flag="flags/ukraine.svg" value="UA">Ukraine</option>
                                <option data-kt-flag="flags/united-arab-emirates.svg" value="AE">United Arab Emirates</option>
                                <option data-kt-flag="flags/united-kingdom.svg" value="GB">United Kingdom</option>
                                <option data-kt-flag="flags/united-states.svg" value="US">United States</option>
                                <option data-kt-flag="flags/uruguay.svg" value="UY">Uruguay</option>
                                <option data-kt-flag="flags/uzbekistan.svg" value="UZ">Uzbekistan</option>
                                <option data-kt-flag="flags/vanuatu.svg" value="VU">Vanuatu</option>
                                <option data-kt-flag="flags/venezuela.svg" value="VE">Venezuela, Bolivarian Republic of</option>
                                <option data-kt-flag="flags/vietnam.svg" value="VN">Vietnam</option>
                                <option data-kt-flag="flags/virgin-islands.svg" value="VI">Virgin Islands</option>
                                <option data-kt-flag="flags/yemen.svg" value="YE">Yemen</option>
                                <option data-kt-flag="flags/zambia.svg" value="ZM">Zambia</option>
                                <option data-kt-flag="flags/zimbabwe.svg" value="ZW">Zimbabwe</option>
                            </select>
                        </div>
                        @error('country_code')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                        <!--end::Col-->
                    </div>

                    <!--end::Input group-->
                    <!--begin::Input group-->
                    {{-- <div class="row mb-0">
                        <label class="col-lg-4 col-form-label fw-semibold fs-6">Allow Marketing</label>
                        <div class="col-lg-8 d-flex align-items-center">
                            <div class="form-check form-check-solid form-switch form-check-custom fv-row">
                                <input class="form-check-input w-45px h-30px" type="checkbox" id="allowmarketing" checked="checked" />
                                <label class="form-check-label" for="allowmarketing"></label>
                            </div>
                        </div>
                    </div> --}}
                    <!--end::Input group-->
                </div>
                <!--end::Card body-->
                <!--begin::Actions-->
                <div class="card-footer d-flex justify-content-end py-6 px-9">
                    <button type="submit" class="btn btn-primary" id="kt_account_profile_details_submit" wire:click="updateUserProfile" wire:loading.attr="disabled">Save Changes
                        <span wire:loading.class="spinner-border spinner-border-sm ms-2" wire:target="updateUserProfile"></span>
                    </button>
                </div>
                <!--end::Actions-->
            </form>
            <!--end::Form-->
        </div>
        <!--end::Content-->
    </div>
    <!--end::Basic info-->
    <!--begin::Sign-in Method-->
    <div class="card mb-5 mb-xl-10">
        <!--begin::Card header-->
        <div class="card-header border-0">
            <div class="card-title m-0">
                <h3 class="fw-bold m-0">Sign-in Method</h3>
            </div>
        </div>
        <!--end::Card header-->
        <!--begin::Content-->
        <div id="kt_account_settings_signin_method">
            <!--begin::Card body-->
            <div class="card-body border-top p-9">
                <!--begin::Email Address-->
                <div class="d-flex flex-wrap align-items-center">
                    <!--begin::Label-->
                    <div id="kt_signin_email" class="{{ $showEmailEdit ? 'd-none' : '' }}">
                        <div class="fs-6 fw-bold mb-1">Email Address</div>
                        <div class="fw-semibold text-gray-600">{{ $authUser->email }}</div>
                    </div>
                    <!--end::Label-->
                    <!--begin::Edit-->
                    <div id="kt_signin_email_edit" class="flex-row-fluid {{ $showEmailEdit ? '' : 'd-none' }}">
                        <!--begin::Form-->
                        <form id="kt_signin_change_email" class="form" novalidate="novalidate">
                            <div class="row mb-6">
                                <div class="col-lg-6 mb-4 mb-lg-0">
                                    <div class="fv-row mb-0">
                                        <label for="emailaddress" class="form-label fs-6 fw-bold mb-3">Enter New Email Address</label>
                                        <input type="email" wire:model="email" class="form-control form-control-lg form-control-solid" id="emailaddress" placeholder="Email Address" name="emailaddress" value="{{ $authUser->email }}" />
                                        @error('email')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex">
                                <button id="kt_signin_submit" type="button" wire:click="updateEmail" class="btn btn-primary me-2 px-6" wire:loading.attr="disabled">Update Email
                                    <span wire:loading.class="spinner-border spinner-border-sm ms-2" wire:target="updateEmail"></span>
                                </button>
                                <button id="kt_signin_cancel" type="button" class="btn btn-color-gray-500 btn-active-light-primary px-6" wire:click="toggleEmailEdit" wire:loading.attr="disabled">
                                    Cancel
                                    <span wire:loading.class="spinner-border spinner-border-sm ms-2" wire:target="toggleEmailEdit"></span>
                                </button>
                            </div>
                        </form>
                        <!--end::Form-->
                    </div>
                    <!--end::Edit-->
                    <!--begin::Action-->
                    <div id="kt_signin_email_button" class="ms-auto {{ $showEmailEdit ? 'd-none' : '' }}">
                        <button class="btn btn-light btn-active-light-primary" wire:click="toggleEmailEdit" wire:loading.attr="disabled">
                            Change Email
                            <span wire:loading.class="spinner-border spinner-border-sm ms-2" wire:target="toggleEmailEdit"></span>
                        </button>
                    </div>
                    <!--end::Action-->
                </div>
                <!--end::Email Address-->
                <!--begin::Separator-->
                <div class="separator separator-dashed my-6"></div>
                <!--end::Separator-->
                <!--begin::Password-->
                <div class="d-flex flex-wrap align-items-center mb-10">
                    <!--begin::Label-->
                    <div id="kt_signin_password" class="{{ $showPasswordEdit ? 'd-none' : '' }}">
                        <div class="fs-6 fw-bold mb-1">Password</div>
                        <div class="fw-semibold text-gray-600">************</div>
                    </div>
                    <!--end::Label-->
                    <!--begin::Edit-->
                    <div id="kt_signin_password_edit" class="flex-row-fluid {{ $showPasswordEdit ? '' : 'd-none' }}">
                        <!--begin::Form-->
                        <form id="kt_signin_change_password" class="form" novalidate="novalidate">
                            <div class="row mb-1">
                                <div class="col-lg-4">
                                    <div class="fv-row mb-0">
                                        <label for="currentpassword" class="form-label fs-6 fw-bold mb-3">Current Password</label>
                                        <input type="password" wire:model="currentPassword" class="form-control form-control-lg form-control-solid" id="currentpassword" />
                                        @error('currentPassword')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="fv-row mb-0">
                                        <label for="newpassword" class="form-label fs-6 fw-bold mb-3">New Password</label>
                                        <input type="password" wire:model="password" class="form-control form-control-lg form-control-solid" id="newpassword" />
                                        @error('password')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="fv-row mb-0">
                                        <label for="confirmpassword" class="form-label fs-6 fw-bold mb-3">Confirm New Password</label>
                                        <input type="password" wire:model="confirmPassword" class="form-control form-control-lg form-control-solid" id="confirmpassword" />
                                        @error('confirmPassword')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="form-text mb-5">Password must be at least 8 character and contain symbols</div>
                            <div class="d-flex">
                                <button id="kt_password_submit" type="button" wire:click="updatePassword" class="btn btn-primary me-2 px-6" wire:loading.attr="disabled">Update Password
                                    <span wire:loading.class="spinner-border spinner-border-sm ms-2" wire:target="updatePassword"></span>
                                </button>
                                <button id="kt_password_cancel" type="button" class="btn btn-color-gray-500 btn-active-light-primary px-6" wire:click="togglePasswordEdit" wire:loading.attr="disabled">
                                    Cancel
                                    <span wire:loading.class="spinner-border spinner-border-sm ms-2" wire:target="togglePasswordEdit"></span>
                                </button>
                            </div>
                        </form>
                        <!--end::Form-->
                    </div>
                    <!--end::Edit-->
                    <!--begin::Action-->
                    <div id="kt_signin_password_button" class="ms-auto {{ $showPasswordEdit ? 'd-none' : '' }}">
                        <button class="btn btn-light btn-active-light-primary" wire:click="togglePasswordEdit" wire:loading.attr="disabled">
                            Change Password
                            <span wire:loading.class="spinner-border spinner-border-sm ms-2" wire:target="togglePasswordEdit"></span>
                        </button>
                    </div>
                    <!--end::Action-->
                </div>
                <!--end::Password-->
                <!--begin::Notice-->
                {{-- <div class="notice d-flex bg-light-primary rounded border-primary border border-dashed p-6">
                    <i class="ki-outline ki-shield-tick fs-2tx text-primary me-4"></i>
                    <div class="d-flex flex-stack flex-grow-1 flex-wrap flex-md-nowrap">
                        <div class="mb-3 mb-md-0 fw-semibold">
                            <h4 class="text-gray-900 fw-bold">Secure Your Account</h4>
                            <div class="fs-6 text-gray-700 pe-7">Two-factor authentication adds an extra layer of security to your account. To log in, in addition you'll need to provide a 6 digit code</div>
                        </div>
                        <a href="#" class="btn btn-primary px-6 align-self-center text-nowrap" data-bs-toggle="modal" data-bs-target="#kt_modal_two_factor_authentication">Enable</a>
                    </div>
                </div> --}}
                <!--end::Notice-->
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Content-->
    </div>
    <!--end::Sign-in Method-->

    <div class="card mb-5 mb-xl-10">
        <!--begin::Card header-->
        <div class="card-header border-0">
            <!--begin::Card title-->
            <div class="card-title m-0">
                <h3 class="fw-bold m-0">Developer Details</h3>
            </div>
            <!--end::Card title-->
        </div>
        <!--begin::Card header-->
        <!--begin::Content-->
        <div id="kt_account_settings_profile_details">
            <!--begin::Form-->
            <form class="form" wire:submit="updateDeveloperProfile">
                <!--begin::Card body-->
                <div class="card-body border-top p-9">

                    {{-- logo --}}
                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label fw-semibold fs-6">Logo</label>
                        <div class="col-lg-8">
                            <img src="{{ asset($developer->logo) }}" alt="Logo" width="140px" class="img-fluid mb-4">
                            <input type="file" wire:model="developerLogo" class="form-control form-control-lg form-control-solid" />
                            @error('developerLogo')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label required fw-semibold fs-6">Name</label>
                        <!--end::Label-->
                        <div class="col-lg-8 fv-row">
                            <input type="text" wire:model="developerName" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="Name" />
                            @error('developerName')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label fw-semibold fs-6">
                            <span class="required">Contact Email</span>
                            <span class="ms-1" data-bs-toggle="tooltip" title="Email must be active">
                                <i class="ki-outline ki-information-5 text-gray-500 fs-6"></i>
                            </span>
                        </label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-lg-8 fv-row">
                            <input type="email" wire:model="developerEmail" class="form-control form-control-lg form-control-solid" placeholder="Email" />
                            @error('developerEmail')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <!--end::Col-->
                    </div>

                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label fw-semibold fs-6">
                            <span class="required">Contact Phone</span>
                            <span class="ms-1" data-bs-toggle="tooltip" title="Phone number must be active">
                                <i class="ki-outline ki-information-5 text-gray-500 fs-6"></i>
                            </span>
                        </label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-lg-8 fv-row">
                            <input type="tel" wire:model="developerPhone" class="form-control form-control-lg form-control-solid" placeholder="Phone number" />
                            @error('developerPhone')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <!--end::Col-->
                    </div>

                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label fw-semibold fs-6">
                            <span class="required">Address</span>
                            <span class="ms-1" data-bs-toggle="tooltip" title="Address">
                                <i class="ki-outline ki-information-5 text-gray-500 fs-6"></i>
                            </span>
                        </label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-lg-8 fv-row">
                            <input type="text" wire:model="developerAddress" class="form-control form-control-lg form-control-solid" placeholder="Address" />
                            @error('developerAddress')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <!--end::Col-->
                    </div>

                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label fw-semibold fs-6">
                            <span class="required">Description</span>
                            <span class="ms-1" data-bs-toggle="tooltip" title="Description">
                                <i class="ki-outline ki-information-5 text-gray-500 fs-6"></i>
                            </span>
                        </label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-lg-8 fv-row">
                            <textarea wire:model="developerDescription" class="form-control form-control-lg form-control-solid" placeholder="Description">{{ $developerDescription }}</textarea>
                            @error('developerDescription')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <!--end::Col-->
                    </div>
                </div>
                <!--end::Card body-->
                <!--begin::Actions-->
                <div class="card-footer d-flex justify-content-end py-6 px-9">
                    <button type="submit" class="btn btn-primary" id="kt_account_profile_details_submit" wire:click="updateDeveloperProfile" wire:loading.attr="disabled">Save Changes
                        <span wire:loading.class="spinner-border spinner-border-sm ms-2" wire:target="updateDeveloperProfile"></span>
                    </button>
                </div>
                <!--end::Actions-->
            </form>
            <!--end::Form-->
        </div>
        <!--end::Content-->
    </div>
</div>
