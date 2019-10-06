</!DOCTYPE html>
<html>
    <head>
        <title>Home</title>
        <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
        <link href="{{asset('/css/style.css')}}" rel="stylesheet" type="text/css"/>
        <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    </head>
    <body>
        <div class="container register">
            <div class="alert alert-success alert-block" id="success" hidden>
                <button type="button" class="close" data-dismiss="alert">×</button>
                <p class="text-center"><strong>Registration is success</strong></p>
            </div>
            <div class="alert alert-danger alert-block" id="danger" hidden>
                <button type="button" class="close" onclick="hidDangerInfo();">×</button>
                <p class="text-center" id="dangerText"><strong>Something</strong></p>
            </div>
            <div class="row">
                <div class="col-md-3 register-left">
                    <br/><br/><br/>
                    <input type="submit" id="loginButton" value="Login" hidden/><br/>
                </div>
                <div class="col-md-9 register-right">
                    <!-- <form name="formRegister" action=""> -->
                    <!--<form action="{{ action('UserController@register') }}" method ="post" id="form-register">-->
                    @csrf <!-- {{ csrf_field() }} -->
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                            <h3 class="register-heading">Registration</h3>
                            <div class="row register-form">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="firstName" id="firstName" placeholder="First Name *"/>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="lastName" id="lastName" placeholder="Last Name *" value="" />
                                    </div>
                                    <div class="form-group">
                                        <div class="maxl">
                                            <label class="radio inline"> 
                                                <input type="radio" name="gender" id="gender1" value="m">
                                                <span> Male </span> 
                                            </label>
                                            <label class="radio inline"> 
                                                <input type="radio" name="gender" id="gender2" value="f">
                                                <span>Female </span> 
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="email" class="form-control" name="email" id="email" placeholder="Email *" value="" required/>
                                    </div>
                                    <div class="form-group" style="margin-bottom: 0px;">
                                        <div class="maxl">
                                            <label class="radio inline"> 
                                                <select class="form-control" name="area" id="area">
                                                    <option class="hidden" selected disabled>Area</option>
                                                    <option>+62</option>
                                                </select>
                                            </label>
                                            <label class="radio inline" style="width: 214px;"> 
                                                <input type="text" minlength="10" maxlength="12" name="phoneNumber" id="phoneNumber" class="form-control" placeholder="Phone Number *" value=""/>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <input type="date" name="birthDate" id="birthDate" class="form-control" placeholder="Date of Birth" value="" />
                                    </div>
                                    <input type="submit" class="btnRegister" value="Register" name="buttonRegister" id="buttonRegister" onclick="sendRegistration()"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--</form>-->
                </div>
            </div>
        </div>
        <script type="text/javascript">
            const sendRegistration = async () => {
                const firstNameElmnt = document.getElementById("firstName");
                const lastNameElmnt = document.getElementById("lastName");
                const emailElmnt = document.getElementById("email");
                const phoneNumberElmnt = document.getElementById("phoneNumber");
                const areaElmnt = document.getElementById("area");
                const gender1Elmnt = document.getElementById("gender1");
                const gender2Elmnt = document.getElementById("gender2");
                const birthDateElmnt = document.getElementById("birthDate");
                const btnRegisterElmnt = document.getElementById("buttonRegister");

                const firstName = firstNameElmnt.value;
                const lastName = lastNameElmnt.value;
                const email = emailElmnt.value;
                const phoneNumber = phoneNumberElmnt.value;
                const area = areaElmnt.value;
                const gender1 = gender1Elmnt.checked;
                const gender2 = gender2Elmnt.checked;
                const birthDate = birthDateElmnt.value;

                // Validation
                if (area === "Area") {
                    alert("Area number field is required");
                    return;
                }

                if (phoneNumber === "") {
                    alert("Phone Number field is required");
                    return;
                }

                if (firstName === "") {
                    alert("First name field is required");
                    return;
                }

                if (lastName === "") {
                    alert("Last name field is required");
                    return;
                }

                if (email === "") {
                    alert("Email field is required");
                    return;
                }

                var gender = "";
                if (gender1 == true) {
                    gender = "m";
                }
                if (gender2 == true) {
                    gender = "f";
                }

                $.ajax({
                    method: 'POST', // Type of response and matches what we said in the route
                    url: '/register', // This is the url we gave in the route
                    data: {
                        "_token": "{{ csrf_token() }}",
                        firstName: firstName,
                        lastName: lastName,
                        email: email,
                        birthDate: birthDate,
                        phoneNumber: area + phoneNumber,
                        gender: gender
                    }, // a JSON object to send back
                    success: function (response) {
                        console.log(response)
                        if (response.status == 200) {
                            const successElmnt = document.getElementById('success');
                            successElmnt.removeAttribute("hidden");

                            const loginElmnt = document.getElementById('loginButton');
                            loginElmnt.removeAttribute("hidden");

                            btnRegisterElmnt.className = "";
                            btnRegisterElmnt.className = "btnRegisterDisabled";

                            hidDangerInfo();

                            // Disable form
                            firstNameElmnt.setAttribute("disabled", true);
                            lastNameElmnt.setAttribute("disabled", true);
                            emailElmnt.setAttribute("disabled", true);
                            phoneNumberElmnt.setAttribute("disabled", true);
                            areaElmnt.setAttribute("disabled", true);
                            gender1Elmnt.setAttribute("disabled", true);
                            gender2Elmnt.setAttribute("disabled", true);
                            birthDateElmnt.setAttribute("disabled", true);
                            btnRegisterElmnt.setAttribute("disabled", true);
                        } else {
                            const dangerElmnt = document.getElementById('danger');
                            dangerElmnt.removeAttribute("hidden");

                            const dangerTxtElmnt = document.getElementById('dangerText');
                            if (response.message != "") {
                                dangerTxtElmnt.innerHTML = response.message
                            } else {
                                dangerTxtElmnt.innerHTML = response.error
                            }
                            return;
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) { // What to do if we fail
                        console.log(JSON.stringify(jqXHR));
                        console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                        return;
                    }
                });
            }
            const hidDangerInfo = async () => {
                const dangerElmnt = document.getElementById('danger');
                dangerElmnt.setAttribute("hidden", true);
            }
        </script> 
    </body>
</html>