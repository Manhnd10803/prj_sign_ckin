<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check-in</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="{{ asset('assets/js/signature_pad.umd.min.js') }}"></script>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white shadow-md rounded-lg p-6 w-full max-w-lg">
        <h1 class="text-2xl font-bold text-center text-gray-700 mb-6">Check-in</h1>

        @if (session('success'))
            <p class="text-green-600 mb-4">{{ session('success') }}</p>
        @endif

        <form action="{{ route('checkin.submit') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <!-- Username -->
                <div class="w-full">
                    <label for="username" class="block text-sm font-medium text-gray-700">Username:</label>
                    <input type="text" id="username" name="username" value="{{ old('username') }}"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 outline-none text-[16px] p-1">
                    @error('username')
                        <p class="text-red-600 text-sm">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Date and Type in one row -->
            <div class="flex space-x-4">
                <!-- Date -->
                <div class="w-1/2">
                    <label for="date" class="block text-sm font-medium text-gray-700">Date:</label>
                    <input type="date" id="date" name="date" value="{{ old('date') }}"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 outline-none text-[16px] p-1">
                    @error('date')
                        <p class="text-red-600 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Type -->
                <div class="w-1/2">
                    <label for="type" class="block text-sm font-medium text-gray-700">Type:</label>
                    <input type="text" id="type" name="type" value="{{ old('type') }}"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 outline-none text-[16px] h-[35px] p-1">
                    @error('type')
                        <p class="text-red-600 text-sm">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Start Time and End Time in one row -->
            <div class="flex space-x-4">
                <!-- Start Time -->
                <div class="w-1/2">
                    <label for="start_time" class="block text-sm font-medium text-gray-700">Start Time:</label>
                    <input type="time" id="start_time" name="start_time" value="{{ old('start_time') }}"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 outline-none text-[16px] p-1">
                    @error('start_time')
                        <p class="text-red-600 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                <!-- End Time -->
                <div class="w-1/2">
                    <label for="end_time" class="block text-sm font-medium text-gray-700">End Time:</label>
                    <input type="time" id="end_time" name="end_time" value="{{ old('end_time') }}"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 outline-none text-[16px] p-1">
                    @error('end_time')
                        <p class="text-red-600 text-sm">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Signature -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Signature:</label>
                <div class="border border-gray-300 rounded-md mt-1 bg-white w-full h-40">
                    <canvas id="signatureCanvas" class="w-full h-full"></canvas>
                </div>
                @error('signature')
                    <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror
                <div class="mt-2 flex gap-2">
                    <button type="button" id="clearSignature"
                        class="px-4 py-2 bg-red-500 text-white text-sm rounded-md hover:bg-red-600">Clear</button>
                    <select id="colorPicker" class="px-4 py-2 border border-gray-300 rounded-md text-sm">
                        <option value="#000000">Black</option>
                        <option value="#FF0000">Red</option>
                        <option value="#00FF00">Green</option>
                        <option value="#0000FF">Blue</option>
                    </select>
                </div>
            </div>

            <!-- Hidden Input for Signature -->
            <input type="hidden" name="signature" id="signatureInput" value="{{ old('signature') }}">

            <!-- Submit Button -->
            <div class="flex items-center justify-center space-x-3 mt-4">
                <!-- Nút Back -->
                <a href="{{ route('checkin.index') }}"
                    class="bg-gray-500 text-white px-4 py-2 rounded-md shadow hover:bg-gray-600">
                    Back
                </a>

                <!-- Nút Check-in -->
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md shadow hover:bg-blue-600">
                    Check-in
                </button>
            </div>
        </form>


    </div>

    <script>
        const canvas = document.getElementById('signatureCanvas');
        const signaturePad = new SignaturePad(canvas);

        // Lưu trữ lịch sử nét vẽ
        let undoStack = [];
        let redoStack = [];

        // Hàm đồng bộ kích thước canvas
        function resizeCanvas() {
            const data = signaturePad.toData();
            const ratio = Math.max(window.devicePixelRatio || 1, 1);
            canvas.width = canvas.offsetWidth * ratio;
            canvas.height = canvas.offsetHeight * ratio;
            canvas.getContext('2d').scale(ratio, ratio);
            signaturePad.clear();
            signaturePad.fromData(data);
        }
        resizeCanvas();
        window.addEventListener('resize', resizeCanvas);

        // Clear Signature
        document.getElementById('clearSignature').addEventListener('click', () => {
            undoStack = [];
            redoStack = [];
            signaturePad.clear();
        });

        // Change Color
        document.getElementById('colorPicker').addEventListener('change', (e) => {
            signaturePad.penColor = e.target.value;
        });

        document.querySelector('form').addEventListener('submit', function(e) {
            // Kiểm tra nếu có chữ ký
            if (signaturePad.isEmpty()) {

            } else {
                // Lấy dữ liệu chữ ký và gán vào input ẩn
                const signatureData = signaturePad.toDataURL(); // Base64 của chữ ký
                document.getElementById('signatureInput').value = signatureData;
            }
        });
    </script>

</body>

</html>
