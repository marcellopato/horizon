@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 border-b border-gray-200">
            <h4 class="text-xl font-semibold text-gray-800">{{ $interview->title }}</h4>
        </div>
        <div class="p-6">
                    <div class="interview-info mb-6">
                        <p class="text-lg text-gray-600 mb-4">{{ $interview->description }}</p>
                        
                        <div class="grid md:grid-cols-2 gap-4">
                            <div class="info-item">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-blue-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="font-medium text-gray-700">Questions:</span>
                                    <span class="ml-2 text-gray-600">{{ $interview->questions->count() }}</span>
                                </div>
                            </div>
                            <div class="info-item">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-yellow-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="font-medium text-gray-700">Estimated Time:</span>
                                    <span class="ml-2 text-gray-600">{{ $interview->questions->sum('time_limit') }} minutes</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="questions-preview mb-6">
                        <h5 class="text-lg font-medium text-gray-900 mb-4">Questions Preview:</h5>
                        <div class="space-y-2">
                            @foreach($interview->questions as $question)
                                <div class="border border-gray-200 rounded-lg">
                                    <button class="w-full px-4 py-3 text-left bg-gray-50 hover:bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 flex justify-between items-center"
                                            type="button" 
                                            onclick="toggleQuestion({{ $question->id }})">
                                        <span class="font-medium text-gray-700">Question {{ $question->order }}</span>
                                        <div class="flex items-center space-x-2">
                                            @if($question->time_limit)
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">{{ $question->time_limit }} min</span>
                                            @endif
                                            <svg class="w-5 h-5 text-gray-400 transform transition-transform duration-200" id="arrow-{{ $question->id }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                    </button>
                                    <div id="question-content-{{ $question->id }}" class="hidden px-4 py-3 border-t border-gray-200 bg-white rounded-b-lg">
                                        <p class="text-gray-600">{{ $question->question }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="system-check mb-6">
                        <h5 class="text-lg font-medium text-gray-900 mb-4">System Check</h5>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center">
                                    <div class="mr-3">
                                        <svg id="camera-check" class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <span class="text-gray-700">Camera Access</span>
                                </div>
                                <button class="px-3 py-1 text-sm border border-blue-600 text-blue-600 rounded hover:bg-blue-50" onclick="checkCamera()">
                                    Test Camera
                                </button>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center">
                                    <div class="mr-3">
                                        <svg id="microphone-check" class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <span class="text-gray-700">Microphone Access</span>
                                </div>
                                <button class="px-3 py-1 text-sm border border-blue-600 text-blue-600 rounded hover:bg-blue-50" onclick="checkMicrophone()">
                                    Test Microphone
                                </button>
                            </div>
                        </div>
                        
                        <div id="media-preview" class="mt-4 hidden">
                            <video id="test-video" autoplay muted class="w-full max-h-48 rounded-lg border border-gray-300"></video>
                        </div>
                    </div>

                    <div class="interview-instructions mb-6">
                        <h5 class="text-lg font-medium text-gray-900 mb-4">Instructions</h5>
                        <div class="space-y-3">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-gray-700">Make sure you're in a quiet environment with good lighting</span>
                            </div>
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-gray-700">Look directly at the camera when answering</span>
                            </div>
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-gray-700">You can re-record your answers if needed</span>
                            </div>
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-gray-700">Each question has a time limit - manage your time wisely</span>
                            </div>
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-gray-700">Your interview will be automatically saved as you progress</span>
                            </div>
                        </div>
                    </div>

                    <div class="start-interview-section">
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-blue-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                                <div>
                                    <span class="font-medium text-blue-800">Ready to start?</span>
                                    <span class="text-blue-700"> Once you begin, you'll be taken to the interview interface where you can record your answers to each question.</span>
                                </div>
                            </div>
                        </div>
                        
                        <form action="{{ route('submissions.start', $interview) }}" method="POST">
                            @csrf
                            <div class="flex justify-between items-center">
                                <a href="{{ route('interviews.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                                    </svg>
                                    Back to Interviews
                                </a>
                                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg text-lg flex items-center" id="start-interview-btn" disabled>
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"></path>
                                    </svg>
                                    Start Interview
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
    </div>
</div>

<script>
let cameraOk = false;
let microphoneOk = false;
let testStream = null;

async function checkCamera() {
    try {
        const stream = await navigator.mediaDevices.getUserMedia({ video: true });
        document.getElementById('camera-check').className = 'bi bi-check-circle text-success';
        cameraOk = true;
        
        // Show video preview
        document.getElementById('media-preview').classList.remove('d-none');
        document.getElementById('test-video').srcObject = stream;
        testStream = stream;
        
        checkReadiness();
    } catch (error) {
        console.error('Camera error:', error);
        document.getElementById('camera-check').className = 'bi bi-x-circle text-danger';
        alert('Unable to access camera. Please check your browser permissions.');
    }
}

async function checkMicrophone() {
    try {
        const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
        document.getElementById('microphone-check').className = 'bi bi-check-circle text-success';
        microphoneOk = true;
        
        // Stop the stream as we only needed to test access
        stream.getTracks().forEach(track => track.stop());
        
        checkReadiness();
    } catch (error) {
        console.error('Microphone error:', error);
        document.getElementById('microphone-check').className = 'bi bi-x-circle text-danger';
        alert('Unable to access microphone. Please check your browser permissions.');
    }
}

function checkReadiness() {
    const startBtn = document.getElementById('start-interview-btn');
    if (cameraOk && microphoneOk) {
        startBtn.disabled = false;
        startBtn.classList.add('btn-success');
        startBtn.classList.remove('btn-primary');
    }
}

// Auto-check on page load
document.addEventListener('DOMContentLoaded', async () => {
    try {
        const stream = await navigator.mediaDevices.getUserMedia({ video: true, audio: true });
        
        cameraOk = true;
        microphoneOk = true;
        
        document.getElementById('camera-check').className = 'bi bi-check-circle text-success';
        document.getElementById('microphone-check').className = 'bi bi-check-circle text-success';
        
        // Show video preview
        document.getElementById('media-preview').classList.remove('d-none');
        document.getElementById('test-video').srcObject = stream;
        testStream = stream;
        
        checkReadiness();
    } catch (error) {
        console.warn('Auto-check failed:', error?.name, error?.message);
        // Mantém o manual check, mas dá uma mensagem útil se for permissão negada
        if (error && (error.name === 'NotAllowedError' || error.name === 'SecurityError')) {
            alert('O navegador bloqueou o acesso à câmera/microfone. Clique no ícone de cadeado na barra de endereços e permita o acesso.');
        }
    }
});

// Clean up stream when leaving page
window.addEventListener('beforeunload', () => {
    if (testStream) {
        testStream.getTracks().forEach(track => track.stop());
    }
});

// Para evitar manter a câmera aberta ao iniciar a entrevista
const startForm = document.querySelector('form[action="{{ route('submissions.start', $interview) }}"]');
if (startForm) {
    startForm.addEventListener('submit', () => {
        if (testStream) {
            try { testStream.getTracks().forEach(t => t.stop()); } catch (e) {}
        }
    });
}
</script>

<style>
.info-item {
    display: flex;
    align-items: center;
    gap: 8px;
}

.check-item {
    padding: 8px;
    border-radius: 4px;
    background: #f8f9fa;
}

.check-status {
    width: 20px;
}

.media-preview {
    background: #000;
    border-radius: 8px;
    overflow: hidden;
}
</style>
@endsection