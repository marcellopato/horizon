@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 flex justify-center items-start">
    <div class="flex w-full max-w-6xl mx-auto">
        <!-- Sidebar with Questions -->
    <div class="w-64 bg-white shadow-lg">
            <div class="p-6">
                <h5 class="text-xl font-bold text-gray-900">{{ $submission->interview->title }}</h5>
                <p class="text-gray-600 text-sm mt-2">{{ $submission->interview->description }}</p>
                
                <hr class="my-4 border-gray-300">
                
                <div class="mb-6">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-gray-700">Progress</span>
                        <span class="text-sm font-bold text-gray-900">{{ $submission->getProgressPercentage() }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $submission->getProgressPercentage() }}%"></div>
                    </div>
                </div>

                <div class="questions-list">
                    @foreach($submission->interview->questions as $index => $question)
                        @php
                            $answer = $submission->answers()->where('question_id', $question->id)->first();
                            $isCompleted = $answer && $answer->isCompleted();
                            $isActive = request()->get('question') == $question->id || (!request()->get('question') && $index === 0);
                        @endphp
                        
                        <div class="question-item mb-3 p-3 rounded-lg cursor-pointer transition-all hover:translate-x-1 {{ $isActive ? 'bg-blue-500 text-white' : ($isCompleted ? 'bg-green-500 text-white' : 'bg-gray-100 hover:bg-gray-200') }}">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium">Question {{ $question->order }}</span>
                                @if($isCompleted)
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                @elseif($isActive)
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"></path>
                                    </svg>
                                @else
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm0-2a6 6 0 100-12 6 6 0 000 12z" clip-rule="evenodd"></path>
                                    </svg>
                                @endif
                            </div>
                            <div class="text-sm mt-2 {{ $isActive || $isCompleted ? 'text-white' : 'text-gray-600' }}">
                                {{ Str::limit($question->question, 50) }}
                            </div>
                            @if($question->time_limit)
                                <div class="text-xs mt-1 {{ $isActive || $isCompleted ? 'text-white' : 'text-gray-500' }} flex items-center">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V5z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $question->time_limit }} min
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Main Interview Area -->
        <div class="flex-1 bg-white">
            <div class="p-6">
                @php
                    $currentQuestionId = request()->get('question', $submission->interview->questions->first()->id);
                    $currentQuestion = $submission->interview->questions()->where('id', $currentQuestionId)->first();
                    $currentAnswer = $submission->answers()->where('question_id', $currentQuestionId)->first();
                @endphp

                <div class="interview-header mb-8">
                    <div class="flex justify-between items-center">
                        <h4 class="text-3xl font-bold text-gray-900">Question {{ $currentQuestion->order }}</h4>
                        <div class="interview-controls flex items-center space-x-3">
                            @if($currentQuestion->time_limit)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V5z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $currentQuestion->time_limit }} minutes
                                </span>
                            @endif
                            <span id="timer" class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800"></span>
                        </div>
                    </div>
                </div>

                <div class="question-content mb-8">
                    <div class="bg-gray-50 rounded-lg border border-gray-200">
                        <div class="p-6">
                            <h5 class="text-xl font-semibold text-gray-900">{{ $currentQuestion->question }}</h5>
                        </div>
                    </div>
                </div>

                <!-- Video Recording Area -->
                <div class="recording-area">
                    @if($currentAnswer && $currentAnswer->isCompleted())
                        <!-- Show completed answer -->
                        <div class="completed-answer">
                            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="text-green-800 font-medium">Answer recorded successfully!</span>
                                </div>
                                <p class="text-green-700 text-sm mt-1">Duration: {{ $currentAnswer->getFormattedDuration() }}</p>
                            </div>
                            
                            @if($currentAnswer->hasVideo())
                                <video controls class="w-full mb-6 rounded-lg" style="max-height: 400px;">
                                    <source src="{{ $currentAnswer->getVideoUrl() }}" type="{{ $currentAnswer->getVideoMimeType() }}">
                                    Your browser does not support the video tag.
                                </video>
                            @endif
                            
                            <div class="flex space-x-4">
                                <button class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded flex items-center" onclick="retakeVideo()">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"></path>
                                    </svg>
                                    Re-record Answer
                                </button>
                                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded flex items-center" onclick="nextQuestion()">
                                    Next Question
                                    <svg class="w-4 h-4 ml-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @else
                        <!-- Recording interface -->
                        <div class="recording-interface">
                            <div class="video-container mb-8 relative bg-black rounded-xl overflow-hidden shadow-2xl mx-auto" style="max-width: 800px;">
                                <!-- Video wrapper with 16:9 aspect ratio -->
                                <div class="relative w-full" style="padding-bottom: 56.25%;">
                                    <video id="preview" autoplay muted class="absolute inset-0 w-full h-full object-cover bg-black"></video>
                                    <video id="recorded" controls class="absolute inset-0 w-full h-full object-cover hidden"></video>
                                    
                                    <!-- Recording indicator overlay -->
                                    <div id="recording-indicator" class="absolute top-4 left-4 hidden">
                                        <div class="flex items-center bg-red-600 text-white px-3 py-1 rounded-full text-sm font-medium">
                                            <div class="w-2 h-2 bg-white rounded-full mr-2 animate-pulse"></div>
                                            REC
                                        </div>
                                    </div>
                                    
                                    <!-- Timer overlay -->
                                    <div id="video-timer" class="absolute top-4 right-4 bg-black bg-opacity-75 text-white px-3 py-1 rounded-full text-sm font-mono hidden">
                                        00:00
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Recording Controls Panel -->
                            <div class="recording-controls bg-white rounded-xl shadow-lg border border-gray-200 p-8 mx-auto" style="max-width: 800px;">
                                <!-- Status Display -->
                                <div class="recording-status mb-8 text-center">
                                    <div id="recording-timer-display" class="hidden">
                                        <div class="inline-flex items-center bg-red-50 border border-red-200 px-4 py-2 rounded-lg">
                                            <div class="w-3 h-3 bg-red-500 rounded-full mr-3 animate-pulse"></div>
                                            <span class="text-red-700 font-semibold">Recording: </span>
                                            <span id="rec-time" class="text-red-900 font-mono ml-1">00:00</span>
                                        </div>
                                    </div>
                                    
                                    <div id="ready-status" class="text-gray-600">
                                        <svg class="w-8 h-8 mx-auto mb-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        <p class="text-sm">Camera ready. Click "Start Recording" when you're ready to begin.</p>
                                    </div>
                                </div>
                                
                                <!-- Control Buttons -->
                                <div class="control-buttons flex flex-wrap justify-center items-center gap-4 mt-2 mb-2">
                                    <button id="start-btn" type="button" class="flex items-center gap-2 px-5 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-semibold shadow transition-all duration-150">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"></path></svg>
                                        Iniciar Gravação
                                    </button>
                                    <button id="stop-btn" type="button" class="flex items-center gap-2 px-5 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white font-semibold shadow transition-all duration-150 hidden">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8 7a1 1 0 012 0v6a1 1 0 11-2 0V7zM12 7a1 1 0 012 0v6a1 1 0 11-2 0V7z" clip-rule="evenodd"></path></svg>
                                        Parar
                                    </button>
                                    <button id="upload-btn" type="button" class="flex items-center gap-2 px-5 py-2 rounded-lg bg-green-600 hover:bg-green-700 text-white font-semibold shadow transition-all duration-150 hidden">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM6.293 6.707a1 1 0 010-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 5.414V13a1 1 0 11-2 0V5.414L7.707 6.707a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                                        Enviar Resposta
                                    </button>
                                    <button id="reset-btn" type="button" class="flex items-center gap-2 px-5 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold shadow transition-all duration-150 hidden">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"></path></svg>
                                        Tentar Novamente
                                    </button>
                                </div>
                                
                                <!-- Help Text -->
                                <div class="mt-6 text-center text-sm text-gray-500">
                                    <p>💡 <strong>Tip:</strong> Position yourself in the center of the frame and speak clearly towards the camera.</p>
                                </div>
                            </div>
                            
                            <!-- Fullscreen upload overlay -->
                            <div class="upload-progress hidden fixed inset-0 z-50 bg-black/50 backdrop-blur-sm items-center justify-center">
                                <div class="w-full max-w-md mx-auto bg-white rounded-2xl shadow-2xl p-6 text-center">
                                    <div class="mx-auto w-12 h-12 rounded-full border-4 border-blue-200 border-t-blue-600 animate-spin"></div>
                                    <h3 class="mt-4 text-lg font-semibold text-gray-900">Enviando sua resposta…</h3>
                                    <p class="mt-1 text-sm text-gray-600">Isso pode levar alguns segundos. Não feche esta janela.</p>
                                    <div class="mt-4 w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                                        <div class="progress-bar bg-blue-500 h-2 w-1/3 animate-pulse"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include video recording JavaScript -->
<script>
class InterviewRecorder {
    constructor() {
        this.mediaRecorder = null;
        this.recordedChunks = [];
        this.stream = null;
        this.recordingStartTime = null;
        this.timerInterval = null;
        this.questionTimeLimit = {{ $currentQuestion->time_limit * 60 ?? 0 }}; // em segundos
        this.questionTimer = null;
        
        this.init();
    }
    
    async init() {
        await this.setupCamera();
        this.setupEventListeners();
        this.startQuestionTimer();
    }
    
    async setupCamera() {
        try {
            if (!('mediaDevices' in navigator) || !('getUserMedia' in navigator.mediaDevices)) {
                alert('Seu navegador não suporta captura de câmera/microfone (getUserMedia). Tente atualizar o navegador.');
                return;
            }

            // Alerta sobre contexto seguro
            const isLocalhost = [/^localhost$/i, /^127\.0\.0\.1$/, /^\[::1\]$/].some(rx => rx.test(location.hostname));
            if (!window.isSecureContext && !isLocalhost) {
                console.warn('Insecure context detected. getUserMedia requer HTTPS (exceto em localhost).');
                alert('Para usar câmera e microfone, acesse por HTTPS ou use localhost. Ex.: https://horizon.test ou http://localhost.');
            }

            // Constraints mais permissivas com valores ideais (não obrigatórios)
            const preferredConstraints = {
                video: { width: { ideal: 1280 }, height: { ideal: 720 }, facingMode: 'user' },
                audio: { echoCancellation: true, noiseSuppression: true }
            };

            // Fallback básico
            const fallbackConstraints = { video: true, audio: true };

            try {
                this.stream = await navigator.mediaDevices.getUserMedia(preferredConstraints);
            } catch (err1) {
                console.warn('Preferred constraints failed:', err1);
                try {
                    this.stream = await navigator.mediaDevices.getUserMedia(fallbackConstraints);
                } catch (err2) {
                    this.handleGetUserMediaError(err2);
                    return;
                }
            }

            document.getElementById('preview').srcObject = this.stream;
        } catch (error) {
            this.handleGetUserMediaError(error);
        }
    }
    
    setupEventListeners() {
        document.getElementById('start-btn').addEventListener('click', () => this.startRecording());
        document.getElementById('stop-btn').addEventListener('click', () => this.stopRecording());
        document.getElementById('upload-btn').addEventListener('click', () => this.uploadVideo());
        document.getElementById('reset-btn').addEventListener('click', () => this.resetRecording());
    }
    
    startRecording() {
        this.recordedChunks = [];
        
        // Seleciona o melhor tipo suportado pelo navegador
        const supportedType = this.getSupportedMimeType();
        const options = supportedType ? { mimeType: supportedType } : undefined;
        
        this.mediaRecorder = new MediaRecorder(this.stream, options);
        
        this.mediaRecorder.ondataavailable = (event) => {
            if (event.data.size > 0) {
                this.recordedChunks.push(event.data);
            }
        };
        
        this.mediaRecorder.onstop = () => {
            this.showRecordedVideo();
        };
        
        this.mediaRecorder.start();
        this.recordingStartTime = Date.now();
        this.startRecordingTimer();
        
        // Update UI
        document.getElementById('start-btn').classList.add('hidden');
        document.getElementById('stop-btn').classList.remove('hidden');
        document.getElementById('recording-timer').classList.remove('hidden');
    }
    
    stopRecording() {
        if (this.mediaRecorder && this.mediaRecorder.state === 'recording') {
            this.mediaRecorder.stop();
            this.stopRecordingTimer();
            
            // Update UI
            document.getElementById('stop-btn').classList.add('hidden');
            document.getElementById('recording-timer').classList.add('hidden');
        }
    }
    
    getSupportedMimeType() {
        const types = [
            'video/webm;codecs=vp9',
            'video/webm;codecs=vp8',
            'video/webm'
        ];
        for (const t of types) {
            if (window.MediaRecorder && MediaRecorder.isTypeSupported && MediaRecorder.isTypeSupported(t)) {
                return t;
            }
        }
        return null;
    }

    handleGetUserMediaError(error) {
        console.error('getUserMedia error:', error?.name, error?.message || error);
        let message = 'Não foi possível acessar a câmera e o microfone.';
        switch (error && error.name) {
            case 'NotAllowedError':
            case 'SecurityError':
                message += '\n\nPermissão negada. Verifique:' +
                    '\n• Permissão do navegador (cadeado na barra de endereços).' +
                    '\n• Configurações do Windows: Configurações > Privacidade > Câmera/Microfone (habilitar para o navegador).';
                break;
            case 'NotFoundError':
            case 'DevicesNotFoundError':
                message += '\n\nNenhum dispositivo de câmera/microfone foi encontrado.';
                break;
            case 'OverconstrainedError':
            case 'ConstraintNotSatisfiedError':
                message += '\n\nAs configurações de resolução/áudio solicitadas não são suportadas. Tente novamente.';
                break;
            case 'AbortError':
            case 'NotReadableError':
                message += '\n\nOutro aplicativo pode estar usando a câmera/microfone. Feche Zoom/Teams/OBS/etc. e tente novamente.';
                break;
            default:
                message += '\n\nVerifique se está usando HTTPS ou localhost e tente novamente.';
        }
        alert(message);
    }

    showRecordedVideo() {
        const blob = new Blob(this.recordedChunks, { type: 'video/webm' });
        const url = URL.createObjectURL(blob);
        
        const recordedVideo = document.getElementById('recorded');
        recordedVideo.src = url;
        recordedVideo.classList.remove('hidden');
        
        document.getElementById('preview').classList.add('hidden');
        document.getElementById('upload-btn').classList.remove('hidden');
        document.getElementById('reset-btn').classList.remove('hidden');
    }
    
    resetRecording() {
        // Reset UI
        document.getElementById('preview').classList.remove('hidden');
        document.getElementById('recorded').classList.add('hidden');
        document.getElementById('start-btn').classList.remove('hidden');
        document.getElementById('upload-btn').classList.add('hidden');
        document.getElementById('reset-btn').classList.add('hidden');
        
        // Clear recorded data
        this.recordedChunks = [];
    }
    
    async uploadVideo() {
        const blob = new Blob(this.recordedChunks, { type: 'video/webm' });
        const duration = Math.floor((Date.now() - this.recordingStartTime) / 1000);
        
        const formData = new FormData();
        formData.append('video', blob, 'answer.webm');
        formData.append('submission_id', {{ $submission->id }});
        formData.append('question_id', {{ $currentQuestionId }});
        formData.append('duration', duration);
        
    // Show upload progress overlay
    const overlay = document.querySelector('.upload-progress');
    overlay.classList.remove('hidden');
    overlay.classList.add('flex');
        
        try {
            const response = await fetch('{{ route("submissions.upload-video") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            
            const result = await response.json();
            
            if (result.success) {
                // Reload page to show completed state
                window.location.reload();
            } else {
                alert('Upload failed. Please try again.');
            }
        } catch (error) {
            console.error('Upload error:', error);
            alert('Upload failed. Please try again.');
        } finally {
            overlay.classList.add('hidden');
            overlay.classList.remove('flex');
        }
    }
    
    startRecordingTimer() {
        this.timerInterval = setInterval(() => {
            const elapsed = Math.floor((Date.now() - this.recordingStartTime) / 1000);
            const minutes = Math.floor(elapsed / 60);
            const seconds = elapsed % 60;
            document.getElementById('rec-time').textContent = 
                `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        }, 1000);
    }
    
    stopRecordingTimer() {
        if (this.timerInterval) {
            clearInterval(this.timerInterval);
            this.timerInterval = null;
        }
    }
    
    startQuestionTimer() {
        if (this.questionTimeLimit > 0) {
            let timeLeft = this.questionTimeLimit;
            
            const updateTimer = () => {
                if (timeLeft <= 0) {
                    document.getElementById('timer').textContent = 'Time\'s up!';
                    document.getElementById('timer').classList.remove('bg-yellow-100', 'text-yellow-800');
                    document.getElementById('timer').classList.add('bg-red-100', 'text-red-800');
                    return;
                }
                
                const minutes = Math.floor(timeLeft / 60);
                const seconds = timeLeft % 60;
                document.getElementById('timer').textContent = 
                    `${minutes}:${seconds.toString().padStart(2, '0')} left`;
                
                if (timeLeft <= 60) {
                    document.getElementById('timer').classList.remove('bg-yellow-100', 'text-yellow-800');
                    document.getElementById('timer').classList.add('bg-red-100', 'text-red-800');
                }
                
                timeLeft--;
            };
            
            updateTimer();
            this.questionTimer = setInterval(updateTimer, 1000);
        }
    }
}

// Initialize when page loads
document.addEventListener('DOMContentLoaded', () => {
    // Só inicializa se a interface de gravação existir nesta página
    if (document.getElementById('preview') && document.getElementById('start-btn')) {
        new InterviewRecorder();
    }
});

// Helper functions
function nextQuestion() {
    const questions = @json($submission->interview->questions->pluck('id'));
    const currentIndex = questions.indexOf({{ $currentQuestionId }});
    
    if (currentIndex < questions.length - 1) {
        const nextQuestionId = questions[currentIndex + 1];
        window.location.href = `{{ route('submissions.interview', $submission) }}?question=${nextQuestionId}`;
    } else {
        // Interview completed
        if (confirm('You have completed all questions. Submit your interview?')) {
            // Finaliza a submissão via POST
            fetch(`{{ route('submissions.submit', $submission) }}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            }).then(r => r.json()).then(() => {
                // Redireciona para uma página acessível ao candidato
                window.location.href = '{{ route('interviews.index') }}';
            }).catch(() => {
                window.location.href = '{{ route('interviews.index') }}';
            });
        }
    }
}

function retakeVideo() {
    if (confirm('Are you sure you want to re-record this answer?')) {
        window.location.reload();
    }
}
</script>

<style>
/* TailwindCSS handles most styling, minimal custom CSS needed */
.video-container video {
    object-fit: cover;
}

.progress-bar {
    transition: width 0.3s ease;
}
</style>
@endsection