<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Quiz Live Score</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.8.1/flowbite.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="{{ asset('assets/style/style.css') }}">
    <script src="{{ asset('dist/main.js') }}"></script>
</head>
<body>
<div class="container min-h-screen pb-10 bg-[url('./assets/gif/bg.gif')] my-0 mx-auto">
    <div class="content-top grid grid-cols-3 border-gray-300  h-[50px] py-3">
        <div class="text-left ml-3 mt-2">
        </div>
        <div class="text-center mx-auto mt-2">
            <div class="bg-white w-[100%] float-left mt-[-10px]
            rounded-lg border border-gray-200
            hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-200" style="cursor: pointer;">
                <span class="py-2.5 font-bold float-left pl-5 pr-2 text-gray-900 focus:outline-none" id="examCode">
                PM2AC2EC</span>
                <svg
                        class="w-5 h-5 float-left text-gray-800 mt-3 mr-2 hover:bg-gray-100 hover:text-blue-300 focus:z-10 focus:ring-4 focus:ring-gray-200"
                        aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="currentColor" viewBox="0 0 18 20">
                    <path
                            d="M5 9V4.13a2.96 2.96 0 0 0-1.293.749L.879 7.707A2.96 2.96 0 0 0 .13 9H5Zm11.066-9H9.829a2.98 2.98 0 0 0-2.122.879L7 1.584A.987.987 0 0 0 6.766 2h4.3A3.972 3.972 0 0 1 15 6v10h1.066A1.97 1.97 0 0 0 18 14V2a1.97 1.97 0 0 0-1.934-2Z"/>
                    <path
                            d="M11.066 4H7v5a2 2 0 0 1-2 2H0v7a1.969 1.969 0 0 0 1.933 2h9.133A1.97 1.97 0 0 0 13 18V6a1.97 1.97 0 0 0-1.934-2Z"/>
                </svg>
            </div>
        </div>
        <div class="text-right mr-3 mt-2">
        </div>
    </div>

    <div class="relative overflow-x-auto mt-[8%] w-[80%] mx-auto rounded-lg">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase">
            <tr class="bg-gray-100">
                <th scope="col" class="text-sm px-5 py-2.5" colspan="5">
                    <div class="text-center inline-flex text-1xl items-center">
                        <svg class="w-5 h-5 text-gray-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                             fill="currentColor" viewBox="0 0 20 19">
                            <path
                                    d="M14.5 0A3.987 3.987 0 0 0 11 2.1a4.977 4.977 0 0 1 3.9 5.858A3.989 3.989 0 0 0 14.5 0ZM9 13h2a4 4 0 0 1 4 4v2H5v-2a4 4 0 0 1 4-4Z"/>
                            <path
                                    d="M5 19h10v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2ZM5 7a5.008 5.008 0 0 1 4-4.9 3.988 3.988 0 1 0-3.9 5.859A4.974 4.974 0 0 1 5 7Zm5 3a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm5-1h-.424a5.016 5.016 0 0 1-1.942 2.232A6.007 6.007 0 0 1 17 17h2a1 1 0 0 0 1-1v-2a5.006 5.006 0 0 0-5-5ZM5.424 9H5a5.006 5.006 0 0 0-5 5v2a1 1 0 0 0 1 1h2a6.007 6.007 0 0 1 4.366-5.768A5.016 5.016 0 0 1 5.424 9Z"/>
                        </svg>
                        <span id="totalUser">1</span> người chơi
                    </div>
                </th>
            </tr>
            <tr class="bg-gray-50">
                <th scope="col" class="px-6 py-3">
                    Thứ hạng
                </th>
                <th scope="col" class="px-6 py-3">
                    Tên
                </th>
                <th scope="col" class="px-6 py-3">
                    Tổng thời gian làm bài
                </th>
                <th scope="col" class="px-6 py-3">
                    Điểm
                </th>
                <th scope="col" class="px-6 py-3">
                </th>
            </tr>
            </thead>
            <tbody id="table-body">
            </tbody>
        </table>
    </div>
</div>
<script>
    let url = '{{ env('APP_URL') . '/api/' }}';
    let resultUrl = url + 'results/';
    let examUrl = url + 'exams/';
    let liveScoreUrl = url + 'live_score/';
</script>
<script src="{{ asset('assets/js/main.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.8.1/flowbite.min.js"></script>
</body>
</html>
