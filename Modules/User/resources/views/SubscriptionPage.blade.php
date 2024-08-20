<!DOCTYPE html>
<html lang="en">
<head>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscription Selection</title>
</head>
<body>
    <x-navbar></x-navbar>
    <div class="current-subscription">
    @if ($SubscriptionExists)
        <strong>Current Subscription : </strong> $12 / month 
    @else
        <strong>Current Subscription : </strong> $0 / month
    @endif
    </div>
    <div>
        <h2 class="text-3xl font-bold tracki text-center mt-12 sm:text-5xl">Pricing</h2>
        <p class="max-w-3xl mx-auto mt-4 text-xl text-center ">Get started on our free plan and upgrade when you are
            ready.</p>
    </div>
    <div class="mt-24 container space-y-12 lg:space-y-0 lg:grid lg:grid-cols-2 lg:gap-x-8">
        <div class="relative p-8  border border-gray-200 rounded-2xl shadow-sm flex flex-col">
            <div class="flex-1">
                <h3 class="text-xl font-semibold ">Free</h3>
                <p class="mt-4 flex items-baseline ">
                    <span class="text-5xl font-extrabold tracking-tight">$0</span><span class="ml-1 text-xl font-semibold">/month</span>
                </p>
                <p class="mt-6 ">You just want to discover</p>
                <ul role="list" class="mt-6 space-y-6">
                    <li class="flex"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="flex-shrink-0 w-6 h-6 text-emerald-500" aria-hidden="true">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg><span class="ml-3 ">10 Credits</span></li>
                    <li class="flex"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="flex-shrink-0 w-6 h-6 text-emerald-500" aria-hidden="true">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg><span class="ml-3 ">Generate video (2 credits)</span></li>
                    <li class="flex"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="flex-shrink-0 w-6 h-6 text-emerald-500" aria-hidden="true">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg><span class="ml-3 ">Quizz (1 credits) </span></li>
                </ul>
            </div>
            <form method="post" action="{{route('User.Subscription.deleteData')}}">
                @csrf
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Select</button>
            </form>
        </div>
        <div class="relative p-8  border border-gray-200 rounded-2xl shadow-sm flex flex-col">
            <div class="flex-1">
                <h3 class="text-xl font-semibold ">Pro</h3>
                <p
                    class="absolute top-0 py-1.5 px-4 bg-emerald-500 text-white rounded-full text-xs font-semibold uppercase tracking-wide  transform -translate-y-1/2">
                    Most popular</p>
                <p class="mt-4 flex items-baseline ">
                    <span class="text-5xl font-extrabold tracking-tight">$12</span><span class="ml-1 text-xl font-semibold">/month</span>
                </p>
                <p class="mt-6 ">You want to learn and have a personal assistant</p>
                <ul role="list" class="mt-6 space-y-6">
                    <li class="flex"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="flex-shrink-0 w-6 h-6 text-emerald-500" aria-hidden="true">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg><span class="ml-3 ">30 credits</span></li>
                    <li class="flex"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="flex-shrink-0 w-6 h-6 text-emerald-500" aria-hidden="true">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg><span class="ml-3 ">Powered by GPT-4 (more accurate)</span></li>
                    <li class="flex"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="flex-shrink-0 w-6 h-6 text-emerald-500" aria-hidden="true">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg><span class="ml-3 ">Generate video (2 credits)</span></li>
                    <li class="flex"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="flex-shrink-0 w-6 h-6 text-emerald-500" aria-hidden="true">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg><span class="ml-3 ">Quizz (1 credits) </span></li>
                    <li class="flex"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="flex-shrink-0 w-6 h-6 text-emerald-500" aria-hidden="true">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg><span class="ml-3 ">Analytics on the quizz</span></li>
                </ul>
                <form method="post" action="{{route('User.Subscription.setData')}}">
                    @csrf
                    <button type="submit" class="bg-green-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Select</button>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>