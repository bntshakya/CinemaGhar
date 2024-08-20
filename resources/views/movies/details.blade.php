<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Details</title>
    @vite('resources/css/app.css')
</head>
<body>
<x-navbar></x-navbar>

<div class="relative overflow-x-auto">
    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
        <tbody>
           
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                <img src="{{url('storage/' . $movie->moviepath)}}">
            </tr>
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    Details
                </th>
                <td class="px-6 py-4">
                    {{$movie->details}}
                </td>
            </tr>
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    Cast
                </th>
                <td class="px-6 py-4">
                    {{$movie->cast}}
                </td>
            </tr>
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    Rating
                </th>
                <td class="px-6 py-4">
                    {{$movie->rating}}
                </td>
            </tr>
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    Genre
                </th>
                <td class="px-6 py-4">
                    {{$movie->genre}}
                </td>
            </tr>
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    Runtime
                </th>
                <td class="px-6 py-4">
                    {{$movie->runtime}}
                </td>
            </tr>
        </tbody>
    </table>
</div>
</table>
</body>
</html>