@extends('frontend.layouts.master')
@section('content')
    <style>
        .audio-container {
            width: 200px;
            margin-bottom: 20px;
        }

        .audio-player {
            display: flex;
            align-items: center;
            width: 100%;
        }

        .playPauseBtn {
            width: 30px;
            height: 30px;
            background-color: #ccc;
            border: none;
            cursor: pointer;
            outline: none;
            background-size: contain;
            background-repeat: no-repeat;
        }

        .play {
            background-image: url('play-icon.png');
        }

        .pause {
            background-image: url('pause-icon.png');
        }

        .progress-bar {
            flex-grow: 1;
            height: 10px;
            margin: 0 10px;
            background-color: #ccc;
            border-radius: 5px;
            position: relative;
            cursor: pointer;
        }

        .progress {
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            width: 0;
            background-color: #4caf50;
            border-radius: 5px;
        }

        .currentTime, .duration {
            font-size: 12px;
            width: 40px;
            text-align: center;
        }
    </style>
    <div class="profile overflow-x-hidden">
        <div class="row">
            <div class="col-md-4 p-0">
                <div id="chat">
                    <div class="chat-header p-3 border-bottom shadow d-flex align-items-center justify-content-between">
                        <div
                            class="head d-flex align-items-center justify-content-center gap-3">
                            <img id="userImage" src="{{asset($user->image)}}" class="rounded-pill border"
                                 style="height: 55px;width: 55px">
                            <div>
                                <h5 id="userName" class="fw-bold text-dark">{{$user->name}}</h5>
                                <p class="text-blue m-0">
                                    @if(Cache::has('user-is-online-' . $user->id))
                                        <small class="text-success fw-semibold">متصل</small>
                                    @else
                                        <small
                                            class="text-secondary fw-semibold">{{ Carbon\Carbon::parse($user->last_seen)->diffForHumans() }}</small>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <a href="#" class="text-decoration-none">
                            <i class="fas fa-phone"></i>
                        </a>
                    </div>
                    <div class="py-1 px-3 border-bottom shadow">
                        <a href="{{route('profile.allowed', $user->id)}}"
                           class="text-decoration-none text-light w-100 py-1 d-block text-center rounded-4 bg-main-color">

                            @if($userAllowImage)
                                عدم السماح بمشاهده صورك
                            @else
                                السماح بمشاهدة
                                صورك
                            @endif


                        </a>
                    </div>
                    <div class="chat-body pb-3">

                        @foreach($messages as $message)
                            @if($message->sender == auth()->user()->id)
                                <div class="head d-flex align-items-center gap-3 ps-3 mt-2">
                                    <img src="{{asset(auth()->user()->image)}}" class="rounded-pill border"
                                         style="height: 45px;width: 45px">
                                    <div class="bg-primary rounded-start-0 rounded-4 p-2"
                                         style="border-top-right-radius: 1rem !important;">
                                        <h6 class="fw-bold text-light">{{auth()->user()->name}}</h6>
                                        <p class="text-light m-0">
                                            <small class="">
                                                @if($message->type == 'rec')

                                                    <div class="audio-container">
                                                        <div class="audio-player">
                                                            <button class="playPauseBtn play"><i class="fa fa-play"></i>
                                                            </button>
                                                            <div class="progress-bar">
                                                                <div class="progress"></div>
                                                            </div>
                                                            <span class="currentTime">00:00</span>
                                                        </div>
                                                        <audio class="audio" src="{{asset($message->message)}}"></audio>
                                                    </div>


                                                    <!--<audio controls src="{{asset($message->message)}}"></audio>-->
                                                @else
                                                    {!! $message->message !!}
                                                @endif
                                            </small>
                                        </p>
                                    </div>
                                </div>
                            @else
                                <div dir="ltr"
                                     class="head d-flex align-items-center gap-3 ps-3 mt-2">
                                    <img src="{{asset($user->image)}}" class="rounded-pill border"
                                         style="height: 45px;width: 45px">
                                    <div class="bg-secondary-subtle rounded-end-0 rounded-4 p-2"
                                         style="border-top-left-radius: 1rem !important;">
                                        <h6 class="fw-bold text-black">{{$user->name}}</h6>
                                        <p class="text-black m-0">
                                            <small class="">{!! $message->message !!}</small>
                                        </p>
                                    </div>
                                </div>
                            @endif
                        @endforeach


                        {{--                                                 Receiver --}}
                        {{--                                                <div dir="ltr"--}}
                        {{--                                                    class="head d-flex align-items-center gap-3 ps-3 mt-2">--}}
                        {{--                                                    <img src="{{asset($user->image)}}" class="rounded-pill border"--}}
                        {{--                                                         style="height: 45px;width: 45px">--}}
                        {{--                                                    <div class="bg-secondary-subtle rounded-end-0 rounded-4 p-2" style="border-top-left-radius: 1rem !important;">--}}
                        {{--                                                        <h6 class="fw-bold text-black">{{$user->name}}</h6>--}}
                        {{--                                                        <p class="text-black m-0">--}}
                        {{--                                                            <small class="">ffffffffff</small>--}}
                        {{--                                                        </p>--}}
                        {{--                                                    </div>--}}
                        {{--                                                </div>--}}


                    </div>
                    <div class="chat-footer w-100">
                        <form id="messageForm"
                              class="d-flex w-100 align-items-center justify-content-between gap-2 px-2">
                            <input type="text" value="" name="message" id="message"
                                   class="border text-light bg-dark rounded-pill p-2 px-4 ms-2 w-100"
                                   placeholder="رسالة">
                            <button type="button" class="btn btn-warning px-3 rounded-pill" data-bs-toggle="modal"
                                    data-bs-target="#gift">
                                <i class="fa fa-gift text-light py-1"></i>
                            </button>
                            <button id="send" type="submit" class="d-none btn btn-success px-3 rounded-pill">
                                <i class="fa fa-paper-plane text-light py-1"></i>
                            </button>
                            <button id="start" type="button" class="btn btn-success px-3 rounded-pill">
                                <i class="fa fa-microphone text-light py-1"></i>
                            </button>
                            <button id="stop" type="button" class="btn btn-danger px-3 rounded-pill"
                                    style="display: none">
                                <i class="fa fa-microphone text-light py-1"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-8 bg-main-color py-5">
                <div class="container">
                    <div class="profile-image mx-auto d-flex align-items-center justify-content-center">
                        @if($user->image)
                            <img src="{{asset($user->image)}}" alt=""
                                 class="rounded-circle w-100 h-100 object-fit-contain">
                        @else
                            <i class="fa fa-user fs-2"></i>
                        @endif
                    </div>
                    <div class="text-center mt-3 text-light">
                        <h4 class="fw-bold">{{$user->name}}</h4>
                        <div class="d-flex align-items-center justify-content-center gap-5 mt-3">
                            <span class="fw-bold text-black-50">
                                @if(!empty($user->details()->status))
                                    @if($user->details->status == 1)
                                        آنسة
                                    @elseif($user->details->status == 2)
                                        مطلقة
                                    @elseif($user->details->status == 3)
                                        ارملة
                                    @elseif($user->details->status == 4)
                                        عازب
                                    @elseif($user->details->status == 5)
                                        مطلق
                                    @elseif($user->details->status == 6)
                                        ارمل
                                    @elseif($user->details->status == 7)
                                        متزوج
                                    @endif - {{$user->details->age}} سنة
                                @endif

                            </span>
                        </div>
                        <div class="d-flex align-items-center justify-content-center gap-5 mt-4">
                            <a href="{{route('interest.index', 'ignorance')}}"
                               class="text-decoration-none border border-top-0 border-start-0 py-2 px-4 rounded-pill text-dark shadow-lg d-flex align-items-center gap-3">
                                <b class="m-0">تجاهل</b>
                                <i class="fas fa-thumbs-down text-blue fs-4"></i>
                            </a>
                            <a href="{{route('interest.index', 'interest')}}"
                               class="text-decoration-none border border-top-0 border-start-0 py-2 px-4 rounded-pill text-dark shadow-lg d-flex align-items-center gap-3">
                                <b class="m-0">مهتم</b>
                                <i class="fas fa-thumbs-up text-blue fs-4"></i>
                            </a>
                        </div>
                    </div>
                    <div class="rounded-5 shadow mt-5 pb-5 bg-light">
                        <h4 class="w-100 text-center text-light bg-main-color rounded-0 py-3 rounded-top-5">بطاقة
                            المعلومات</h4>
                        <div class="px-5 mt-3">
                            @if(!empty($user->details))
                                <p class="fw-bold">
                                    <span class="text-black-50">الجنسية</span> :
                                    {{\App\Models\Country::where('id', $user->details->nationality)->first()->name}}
                                </p>
                                <p class="fw-bold">
                                    <span class="text-black-50">مكان الأقامة</span> :
                                    {{\App\Models\Country::where('id', $user->details->country)->first()->name}}
                                </p>
                                <p class="fw-bold">
                                    <span class="text-black-50">المدينة</span> :
                                    {{\App\Models\City::where('id', $user->details->city)->first()->name}}
                                </p>
                                <p class="fw-bold">
                                    <span class="text-black-50">نوع الزواج</span> :
                                    {{$user->details->searching_for == '1' ? 'زواج اولي' : 'زواج ثاني'}}
                                </p>
                                <p class="fw-bold">
                                    <span class="text-black-50">الوزن</span> :
                                    {{$user->details->weight}}
                                </p>
                                <p class="fw-bold">
                                    <span class="text-black-50">العمر</span> :
                                    {{$user->details->age}}
                                </p>
                                <p class="fw-bold">
                                    <span class="text-black-50">المرض</span> :
                                    {{$user->details->health_status}}
                                </p>
                                <p class="fw-bold">
                                    <span class="text-black-50">العمل</span> :
                                    {{$user->details->job}}
                                </p>
                            @endif
                            <p class="fw-bold">
                                <span class="text-black-50">تاريخ الانضمام </span> :
                                {{date('d-m-Y', strtotime($user->created_at))}}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Send Gift -->
    <div class="modal fade" id="gift" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="d-flex align-items-center flex-wrap gap-2">
                        @foreach($gifts as $gift)
                            <a href="{{route('send-gift',[$user->id, $gift->id])}}"
                               class="sendGift border text-decoration-none text-black rounded"
                               style="width:60px; height: 80px;">
                                <div style="width: 60px; height: 60px;">
                                    <img src="{{asset($gift->image)}}" class="w-100 h-100 object-fit-contain">
                                </div>
                                <p class="text-center mb-2 border-top">{{$gift->price}}</p>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>

        $(document).ready(function () {

            setInterval(function () {
                if ($('.emojionearea-editor').html() == '') {
                    $('#start').removeClass('d-none')
                    $('#send').addClass('d-none')
                } else {
                    $('#start').addClass('d-none')
                    $('#send').removeClass('d-none')
                }
            }, 500)


            $('.sendGift').click(function (e) {
                e.preventDefault();

                $.ajax({
                    url: $(this).attr('href'),
                    type: 'post',
                    success: function (data) {

                        if (data.status == 'error') {
                            toastr.error(data.msg)
                        }


                        let sender = `

                            <div class="head d-flex align-items-center gap-3 ps-3 mt-2">
                                <img src="${data.image}" class="rounded-pill border"
                                    style="height: 45px;width: 45px">
                                <div class="bg-primary rounded-start-0 rounded-4 p-2" style="border-top-right-radius: 1rem !important;">
                                    <h6 class="fw-bold text-light">${data.user.name}</h6>
                                    <p class="text-light m-0">
                                        ${data.message.message}
                                    </p>
                                </div>
                            </div>


                        `;

                        $('.chat-body').append(sender)


                    }
                })
                $('#gift').modal('toggle')
            })

            $('#messageForm').submit(function (e) {
                e.preventDefault();

                $.ajax({
                    url: '{{route('send-message', $user->id)}}',
                    type: 'post',
                    data: {
                        message: $('#message').val()
                    },
                    success: function (data) {


                        let sender = `

                            <div class="head d-flex align-items-center gap-3 ps-3 mt-2">
                                <img src="${data.image}" class="rounded-pill border"
                                    style="height: 45px;width: 45px">
                                <div class="bg-primary rounded-start-0 rounded-4 p-2" style="border-top-right-radius: 1rem !important;">
                                    <h6 class="fw-bold text-light">${data.user.name}</h6>
                                    <p class="text-light m-0">
                                        <small class="">${data.message.message}</small>
                                    </p>
                                </div>
                            </div>


                        `;

                        $('.chat-body').append(sender)


                    }
                })


                $('#messageForm').trigger("reset");
            })

        });


        Pusher.logToConsole = true;

        var pusher = new Pusher('7d76fd94f2f40e07f0f1', {
            cluster: 'eu'
        });

        var channel = pusher.subscribe('chat.{{auth()->user()->username .'.'.$user->username}}');
        channel.bind('message', function (data) {


            var receiver = `

             <div dir="ltr"
                                                    class="head d-flex align-items-center gap-3 ps-3 mt-2">
                                                    <img src="${data.image}" class="rounded-pill border"
                                                         style="height: 45px;width: 45px">
                                                    <div class="bg-secondary-subtle rounded-end-0 rounded-4 p-2" style="border-top-left-radius: 1rem !important;">
                                                        <h6 class="fw-bold text-black">{{$user->name}}</h6>
                                                        <p class="text-black m-0">
                                                            <small class="">${data.message.message}</small>
                                                        </p>
                                                    </div>
                                                </div>


            `;


            $('.chat-body').append(receiver)

        });
    </script>


    <script>
        let mediaRecorder;
        let audioChunks = [];
        const startButton = document.getElementById('start');
        const stopButton = document.getElementById('stop');

        async function startRecording() {
            try {
                const stream = await navigator.mediaDevices.getUserMedia({audio: true});
                mediaRecorder = new MediaRecorder(stream);

                mediaRecorder.ondataavailable = event => {
                    audioChunks.push(event.data);
                };

                mediaRecorder.onstop = () => {
                    const audioBlob = new Blob(audioChunks, {'type': 'audio/wav; codecs=opus'});
                    audioChunks = [];
                    const audioUrl = URL.createObjectURL(audioBlob);
                    // audio.src = audioUrl;
                    // message.textContent = "التسجيل جاهز للاستماع.";
                    // toastr.success('التسجيل جاهز للاستماع.')

                    // إرسال التسجيل إلى الخادم
                    const formData = new FormData();
                    formData.append('audio', audioBlob, 'recording.wav');


                    fetch('/chat/send-record/' + '{{$user->id}}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: formData
                    }).then(response => response.text())
                        .then(data => {
                            var res = JSON.parse(data)
                            let message = '';
                            let hostName = document.location.href;

                            if (res.message.type == 'rec') {
                                message = `


                                        <div class="head d-flex align-items-center gap-3 ps-3 mt-2">
                                            <img src="${res.image}" class="rounded-pill border"
                                                style="height: 45px;width: 45px">
                                            <div class="bg-primary rounded-start-0 rounded-4 p-2" style="border-top-right-radius: 1rem !important;">
                                                <h6 class="fw-bold text-light">${res.user.name}</h6>
                                                <p class="text-light m-0">
                                                    <small class="">



                                                        <div class="audio-container">
                                                            <div class="audio-player">
                                                                <button class="playPauseBtn play"><i class="fa fa-play"></i>
                                                                </button>
                                                                <div class="progress-bar">
                                                                    <div class="progress"></div>
                                                                </div>
                                                                <span class="currentTime">00:00</span>
                                                            </div>
                                                            <audio src="${res.rec}"></audio>
                                                        </div>


                                                    </small>
                                                </p>
                                            </div>
                                        </div>

                            `
                            } else {


                                message = `

                                        <div class="head d-flex align-items-center gap-3 ps-3 mt-2">
                                            <img src="${res.image}" class="rounded-pill border"
                                                style="height: 45px;width: 45px">
                                            <div class="bg-primary rounded-start-0 rounded-4 p-2" style="border-top-right-radius: 1rem !important;">
                                                <h6 class="fw-bold text-light">${res.user.name}</h6>
                                                <p class="text-light m-0">
                                                    <small class="">



                                                        ${res.message.message}


                                                    </small>
                                                </p>
                                            </div>
                                        </div>


                                    `;


                            }

                            $
                            ('.chat-body').append(message)


                            toastr.success(data.msg)
                            // message.textContent = data;
                        }).catch(error => {
                        console.error('Error:', error);
                        toastr.error('حدث خطأ أثناء رفع الملف.')
                        // message.textContent = "حدث خطأ أثناء رفع الملف.";
                    });
                };

                mediaRecorder.start();
                // startButton.disabled = true;
                // stopButton.disabled = false;
                // stopButton.classList.remove('d-none')
                // startButton.classList.add('d-none')
                // message.textContent = "التسجيل قيد التشغيل...";
                toastr.success('التسجيل قيد التشغيل...')
            } catch (error) {
                console.error('Error accessing media devices.', error);
                alert('حدث خطأ أثناء الوصول إلى أجهزة التسجيل: ' + error.message);
                message.textContent = "لا يمكن الوصول إلى الميكروفون. تأكد من أن المتصفح لديه الأذونات اللازمة.";
            }
        }

        function stopRecording() {
            if (mediaRecorder && mediaRecorder.state !== "inactive") {
                mediaRecorder.stop();
                // startButton.disabled = false;
                // stopButton.disabled = true;
                // startButton.classList.remove('d-none')
                // stopButton.classList.add('d-none')
                // message.textContent = "تم إيقاف التسجيل.";
                toastr.success('تم إيقاف التسجيل.')
            }
        }

        $(document).ready(function () {
            $('#start').click(function () {
                startRecording();
                // $('#stop').addClass('d-block')
                $('#stop').css('display', 'block')
                $('#start').css('display', 'none')
                // $('#start').removeClass('d-block')
            })
            $('#stop').click(function () {
                stopRecording();
                // $('#start').addClass('d-block')
                $('#start').css('display', 'block')
                $('#stop').css('display', 'none')
                // $('#stop').removeClass('d-block')
            })
        })


        document.addEventListener('DOMContentLoaded', () => {
            const audioContainers = document.querySelectorAll('.audio-container');

            audioContainers.forEach(container => {
                const audio = container.querySelector('.audio');
                const playPauseBtn = container.querySelector('.playPauseBtn');
                const progressBar = container.querySelector('.progress-bar');
                const progress = container.querySelector('.progress');
                const currentTimeElem = container.querySelector('.currentTime');
                const durationElem = container.querySelector('.duration');

                // التحقق من تحميل الميتاداتا قبل محاولة الوصول إلى مدة الصوت
                audio.addEventListener('loadedmetadata', () => {
                    durationElem.textContent = formatTime(audio.duration);
                });

                // تحديث شريط التقدم والوقت الحالي
                audio.addEventListener('timeupdate', () => {
                    updateProgress(audio, progress, currentTimeElem);
                });

                // التحكم في تشغيل/إيقاف الصوت
                playPauseBtn.addEventListener('click', () => {
                    if (audio.paused) {
                        pauseAllAudio();
                        audio.play();
                        playPauseBtn.classList.remove('play');
                        playPauseBtn.classList.add('pause');
                    } else {
                        audio.pause();
                        playPauseBtn.classList.remove('pause');
                        playPauseBtn.classList.add('play');
                    }
                });

                // التحكم في شريط التقدم
                progressBar.addEventListener('click', (e) => {
                    const width = progressBar.clientWidth;
                    const clickX = e.offsetX;
                    const duration = audio.duration;

                    audio.currentTime = (clickX / width) * duration;
                });

                function updateProgress(audio, progress, currentTimeElem) {
                    const currentTime = audio.currentTime;
                    const duration = audio.duration;
                    if (isFinite(duration)) {
                        const progressPercent = (currentTime / duration) * 100;
                        progress.style.width = `${progressPercent}%`;
                        currentTimeElem.textContent = formatTime(currentTime);
                    }
                }

                function formatTime(seconds) {
                    if (!isFinite(seconds)) {
                        return '00:00';
                    }

                    const minutes = Math.floor(seconds / 60);
                    const remainingSeconds = Math.floor(seconds % 60);
                    const formattedMinutes = minutes < 10 ? `0${minutes}` : minutes;
                    const formattedSeconds = remainingSeconds < 10 ? `0${remainingSeconds}` : remainingSeconds;

                    return `${formattedMinutes}:${formattedSeconds}`;
                }

                function pauseAllAudio() {
                    audioContainers.forEach(container => {
                        const audio = container.querySelector('.audio');
                        const playPauseBtn = container.querySelector('.playPauseBtn');
                        if (!audio.paused) {
                            audio.pause();
                            playPauseBtn.classList.remove('pause');
                            playPauseBtn.classList.add('play');
                        }
                    });
                }
            });
        });


    </script>
@endsection
