<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الصوت</title>
</head>
<body>
<h1>تسجيل الصوت</h1>
<button id="start">ابدأ التسجيل</button>
<button id="stop" disabled>أوقف التسجيل</button>
<audio id="audio" controls></audio>
<p id="message"></p>

<script>
    let mediaRecorder;
    let audioChunks = [];
    const startButton = document.getElementById('start');
    const stopButton = document.getElementById('stop');
    const audio = document.getElementById('audio');
    const message = document.getElementById('message');

    async function startRecording() {
        try {
            const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
            mediaRecorder = new MediaRecorder(stream);

            mediaRecorder.ondataavailable = event => {
                audioChunks.push(event.data);
            };

            mediaRecorder.onstop = () => {
                const audioBlob = new Blob(audioChunks, { 'type': 'audio/wav; codecs=opus' });
                audioChunks = [];
                const audioUrl = URL.createObjectURL(audioBlob);
                audio.src = audioUrl;
                message.textContent = "التسجيل جاهز للاستماع.";

                // إرسال التسجيل إلى الخادم
                const formData = new FormData();
                formData.append('audio', audioBlob, 'recording.wav');
                fetch('/rec', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: formData
                }).then(response => response.text())
                    .then(data => {
                        message.textContent = data;
                    }).catch(error => {
                    console.error('Error:', error);
                    message.textContent = "حدث خطأ أثناء رفع الملف.";
                });
            };

            mediaRecorder.start();
            startButton.disabled = true;
            stopButton.disabled = false;
            message.textContent = "التسجيل قيد التشغيل...";
        } catch (error) {
            console.error('Error accessing media devices.', error);
            alert('حدث خطأ أثناء الوصول إلى أجهزة التسجيل: ' + error.message);
            message.textContent = "لا يمكن الوصول إلى الميكروفون. تأكد من أن المتصفح لديه الأذونات اللازمة.";
        }
    }

    function stopRecording() {
        if (mediaRecorder && mediaRecorder.state !== "inactive") {
            mediaRecorder.stop();
            startButton.disabled = false;
            stopButton.disabled = true;
            message.textContent = "تم إيقاف التسجيل.";
        }
    }

    startButton.addEventListener('click', startRecording);
    stopButton.addEventListener('click', stopRecording);
</script>
</body>
</html>
