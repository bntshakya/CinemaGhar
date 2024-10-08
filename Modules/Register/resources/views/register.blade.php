<!DOCTYPE html>
<html lang="en">

<head>
    @vite('resources/css/app.css')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<body class="bg-gray-100 dark:bg-gray-900">
    <x-navbar></x-navbar>
    <div class="flex justify-center items-center h-screen">
        <div class="max-w-md w-full mx-auto bg-white dark:bg-gray-800 p-8 rounded-lg shadow-md ">
            <h2 class="text-2xl font-bold mb-8 text-gray-800 dark:text-white">Register</h2>
            @if(session('status'))
                <div class="mb-4 text-sm font-medium text-red-600">
                    {{ session('status') }}
                </div>
            @endif
            <form method="post" action="{{route('register')}}" id="registerForm">
                @csrf
                <div class="mb-6">
                    <label for="name"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">UserName</label>
                    <input type="text" id="name" name="username"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="John Doe" required />
                </div>
                <div class="mb-6">
                    <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email
                        address</label>
                    <input type="email" id="email" name="email"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="john.doe@company.com" required />
                </div>
                <div class="mb-6">
                    <label for="password"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password</label>
                    <input type="password" id="password" name="password"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="•••••••••" required />
                </div>
                <div class="mb-6">
                    <label for="confirm_password"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="•••••••••" required />
                </div>
               
                <div id="passworderror" class="text-red-500" style="display:none;">Passwords dont match</div>
                <button type="submit" id="registerBtn"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Register</button>
            
   
    <div class="modal">
        <div id="default-modal" tabindex="-1" aria-hidden="true"
            class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div role="status" id="spinner" class="spinner">
                <svg aria-hidden="true" class="w-8 h-8 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600"
                    viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                        fill="currentColor" />
                    <path
                        d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                        fill="currentFill" />
                </svg>
                <span class="sr-only">Loading...</span>
            </div>
            <div class="max-w-md mx-auto text-center bg-white px-4 sm:px-8 py-10 rounded-xl shadow">
                <header class="mb-8">
                    <h1 class="text-2xl font-bold mb-1">Mobile Phone Verification</h1>
                    <p class="text-[15px] text-slate-500">Enter the 4-digit verification code that was sent to your phone
                        number.</p>
                </header>
                <div class="flex items-center justify-center gap-3">
                    <input type="text" id="otp1"
                        class="otp w-14 h-14 text-center text-2xl font-extrabold text-slate-900 bg-slate-100 border border-transparent hover:border-slate-200 appearance-none rounded p-4 outline-none focus:bg-white focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100"
                        pattern="\d*" maxlength="1" />
                    <input type="text" id="otp2"
                        class="otp w-14 h-14 text-center text-2xl font-extrabold text-slate-900 bg-slate-100 border border-transparent hover:border-slate-200 appearance-none rounded p-4 outline-none focus:bg-white focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100"
                        maxlength="1" />
                    <input type="text" id="otp3"
                        class="otp w-14 h-14 text-center text-2xl font-extrabold text-slate-900 bg-slate-100 border border-transparent hover:border-slate-200 appearance-none rounded p-4 outline-none focus:bg-white focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100"
                        maxlength="1" />
                    <input type="text" id="otp4"
                        class="otp w-14 h-14 text-center text-2xl font-extrabold text-slate-900 bg-slate-100 border border-transparent hover:border-slate-200 appearance-none rounded p-4 outline-none focus:bg-white focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100"
                        maxlength="1" />
                </div>
                <div class="max-w-[260px] mx-auto mt-4">
                    <button type="submit" id="verifyBtn"
                        class="w-full inline-flex justify-center whitespace-nowrap rounded-lg bg-indigo-500 px-3.5 py-2.5 text-sm font-medium text-white shadow-sm shadow-indigo-950/10 hover:bg-indigo-600 focus:outline-none focus:ring focus:ring-indigo-300 focus-visible:outline-none focus-visible:ring focus-visible:ring-indigo-300 transition-colors duration-150">Verify
                        Account</button>
                </div>
                <div class="text-sm text-slate-500 mt-4">Didn't receive code? <button type="button" id="resendBtn"
                        class="font-medium text-indigo-500 hover:text-indigo-600" href="#0">Resend</button></div>
            </div>
        </div>
    </div>
    </form>
</div>
</div>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const form = document.getElementById('registerForm')
                const inputs = [...form.querySelectorAll('input[type=text].otp')];
                const submit = form.querySelector('#verifyBtn');

                const handleKeyDown = (e) => {
                    if (
                        !/^[0-9]{1}$/.test(e.key)
                        && e.key !== 'Backspace'
                        && e.key !== 'Delete'
                        && e.key !== 'Tab'
                        && !e.metaKey
                    ) {
                        e.preventDefault()
                    }

                    if (e.key === 'Delete' || e.key === 'Backspace') {
                        const index = inputs.indexOf(e.target);
                        if (index > 0) {
                            inputs[index - 1].value = '';
                            inputs[index - 1].focus();
                        }
                    }
                }

                const handleInput = (e) => {
                    console.log('12');
                    
                    const { target } = e
                    const index = inputs.indexOf(target)
                    if (target.value) {
                        if (index < inputs.length - 1) {
                            inputs[index + 1].focus()
                        } else {
                            submit.focus()
                        }
                    }
                }

                const handleFocus = (e) => {
                    e.target.select()
                }

                const handlePaste = (e) => {
                    e.preventDefault()
                    const text = e.clipboardData.getData('text')
                    if (!new RegExp(`^[0-9]{${inputs.length}}$`).test(text)) {
                        return
                    }
                    const digits = text.split('')
                    inputs.forEach((input, index) => input.value = digits[index])
                    submit.focus()
                }

                inputs.forEach((input) => {
                    input.addEventListener('input', handleInput)
                    input.addEventListener('keydown', handleKeyDown)
                    input.addEventListener('focus', handleFocus)
                    input.addEventListener('paste', handlePaste)
                })
            })                        

        $(document).ready(function () {
            var pinId = null;
            $('#registerBtn').on('click', function (e) {
                e.preventDefault();
                var password = $('#password').val();
                var confirmPassword = $('#confirm_password').val();
                $.post('{{route('passwords.check')}}', {
                    _token: "{{csrf_token()}}",
                    password: password,
                    confirmPassword: confirmPassword
                }, function (response) {
                    if (!response.match) {
                        alert('passwords dont match');
                    }
                    else {
                        const $targetEl = document.getElementById('default-modal');

                        // options with default values
                        const options = {
                            placement: 'bottom-right',
                            backdrop: 'dynamic',
                            backdropClasses:
                                'bg-gray-900/50 dark:bg-gray-900/80 fixed inset-0 z-40',
                            closable: true,
                        };

                        // instance options object
                        const instanceOptions = {
                            id: 'default-modal',
                            override: true
                        };
                        const modal = new Modal($targetEl, options, instanceOptions);
                        modal.show();
                        $.post('{{route('otp.verify')}}', {
                            _token: '{{csrf_token()}}',
                        }, function (response) {
                            console.log(response);
                            const parsedResponse = JSON.parse(response);
                            const appId = parsedResponse.applicationId;
                            
                            $.post('{{route('otp.message.template')}}',{
                               _token: '{{csrf_token()}}',
                               appId:appId,
                            },function(response){                            
                                const messageTemplateResponse =JSON.parse(response);
                                const msgId = messageTemplateResponse.messageId;
                                // console.log(messageTemplateResponse);
                                $.post('{{route('otp.message.deliver')}}',{
                                     _token: '{{csrf_token()}}',
                                     appId: appId,
                                     msgId:msgId,
                                },function(response){
                                    const pinResponse =JSON.parse(response);
                                    pinId = pinResponse.pinId;
                                })
                            });
                        })
                    }
                });
            })

            $('#verifyBtn').on('click', function (event) {
                event.preventDefault();
                // Get values from OTP input fields
                const otp1 = $('#otp1').val();
                const otp2 = $('#otp2').val();
                const otp3 = $('#otp3').val();
                const otp4 = $('#otp4').val();

                // Concatenate values into a single string
                const otpCode = `${otp1}${otp2}${otp3}${otp4}`;
                
                // Optional: Add validation to ensure all fields are filled
                if (otpCode.length !== 4) {
                    alert('Please enter all 4 digits of the OTP.');
                    return;
                }

                $('#spinner').show();
                                
                // Perform OTP verification (replace with your actual verification logic)
                $.post('{{route('otp.code.verify')}}', {
                    _token: '{{csrf_token()}}',
                    pinCode: otpCode,
                    pinId:pinId,
                }, function (response) {
                    JsonparsedResponse =JSON.parse(response)
                    if(JsonparsedResponse.verified === true){
                        $('#registerForm').submit();
                    }else if(JsonparsedResponse.verified === false){
                        $('#spinner').hide();
                        alert('Incorrect OTP provided');
                    }
                });
            });

            $('#resendBtn').on('click',(event)=>{
                event.preventDefault();
                console.log(pinId,'pid');
                
                $.post("{{route('otp.resend')}}",
                {
                    _token: '{{csrf_token()}}',
                    pinId:pinId,
                },
                )
            })
        });
    </script>
    <style>
        .spinner {
            display: none;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1050; /* Higher than modal */
            align-items: center;
            justify-content: center;
        }
    </style>
</body>
</html>