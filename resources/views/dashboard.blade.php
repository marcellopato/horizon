<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }} - {{ ucfirst(auth()->user()->role) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(auth()->user()->canManageInterviews())
                <!-- Admin/Reviewer Dashboard -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-semibold mb-4">{{ __('Interview Management') }}</h3>
                        <p class="text-gray-600 mb-4">{{ __('Welcome! You can create and manage interviews here.') }}</p>
                        
                        @isset($stats)
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <p class="text-sm text-gray-600">{{ __('Total Interviews') }}</p>
                                    <p class="text-2xl font-bold text-gray-800">{{ $stats['interviews_count'] ?? 0 }}</p>
                                </div>
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <p class="text-sm text-gray-600">{{ __('Pending Reviews') }}</p>
                                    <p class="text-2xl font-bold text-yellow-700">{{ $stats['pending_reviews_count'] ?? 0 }}</p>
                                </div>
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <p class="text-sm text-gray-600">{{ __('Reviewed') }}</p>
                                    <p class="text-2xl font-bold text-green-700">{{ $stats['reviewed_count'] ?? 0 }}</p>
                                </div>
                            </div>
                        @endisset
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <a href="{{ route('interviews.create') }}" class="bg-blue-50 p-4 rounded-lg hover:bg-blue-100 transition-colors">
                                <h4 class="font-semibold text-blue-800">{{ __('Create Interview') }}</h4>
                                <p class="text-blue-600 text-sm">{{ __('Set up new video interviews') }}</p>
                            </a>
                            <a href="{{ route('submissions.index') }}" class="bg-green-50 p-4 rounded-lg hover:bg-green-100 transition-colors">
                                <h4 class="font-semibold text-green-800">{{ __('Review Submissions') }}</h4>
                                <p class="text-green-600 text-sm">{{ __('Evaluate candidate responses') }}</p>
                            </a>
                            <a href="{{ route('interviews.index') }}" class="bg-purple-50 p-4 rounded-lg hover:bg-purple-100 transition-colors">
                                <h4 class="font-semibold text-purple-800">{{ __('Manage Interviews') }}</h4>
                                <p class="text-purple-600 text-sm">{{ __('View and edit existing interviews') }}</p>
                            </a>
                        </div>
                        
                        <div class="text-center">
                            <a href="{{ route('interviews.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('View All Interviews') }}
                            </a>
                        </div>
                    </div>
                </div>
            @else
                <!-- Candidate Dashboard -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-semibold mb-4">{{ __('Available Interviews') }}</h3>
                        <p class="text-gray-600 mb-4">{{ __('Welcome! Here are the video interviews available for you.') }}</p>
                        
                        <div class="bg-blue-50 p-6 rounded-lg text-center mb-4">
                            <div class="text-blue-500 mb-2">
                                <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <h4 class="font-semibold text-blue-800 mb-2">{{ __('Ready to start?') }}</h4>
                            <p class="text-blue-600 text-sm mb-4">{{ __('View available interviews and start recording your responses.') }}</p>
                        </div>
                        
                        <div class="text-center">
                            <a href="{{ route('interviews.index') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('View Available Interviews') }}
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            <!-- User Info -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-2">{{ __('Welcome back!') }}</h3>
                    <p class="text-gray-600">
                        {{ __('Hello :name, you are logged in as :role.', [
                            'name' => auth()->user()->name,
                            'role' => ucfirst(auth()->user()->role)
                        ]) }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
