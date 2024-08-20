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
    <div class="flex justify-center items-center h-screen">
        <div class="max-w-md w-full mx-auto bg-white dark:bg-gray-800 p-8 rounded-lg shadow-md ">
            <h2 class="text-2xl font-bold mb-8 text-gray-800 dark:text-white">Register</h2>
            @if(session('status'))
                <div class="mb-4 text-sm font-medium text-red-600">
                    {{ session('status') }}
                </div>
            @endif
            <form method="post" action="{{route('admin.Register.Verification')}}">
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
                        <select name="role[]">
                            @foreach ($roles as $role)
                                <option value="{{$role}}">{{$role}}</option>
                            @endforeach
                        </select>
                

                <div id="passworderror" class="text-red-500" style="display:none;">Passwords dont match</div>
                <button type="submit"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Register</button>
            </form>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            $('form').on('submit', function (e) {
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
                        $('form').unbind('submit').submit();
                    }
                });
            })
        });
    </script>
</body>

</html>