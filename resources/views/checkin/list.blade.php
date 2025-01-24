<!-- resources/views/checkins/index.blade.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check-in List</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.3/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@2.8.2" defer></script>
</head>

<body class="bg-gray-100 min-h-screen flex justify-center">
    <div class="bg-white shadow-md rounded-lg p-6 w-full">
        <h1 class="text-2xl font-bold text-center text-gray-700 mb-6">Check-in List</h1>

        <!-- Nút Back và Create -->
        <div class="mb-4 flex justify-between">
            <div></div>
            <a href="{{ route('checkin.create') }}"
                class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Create</a>
        </div>

        @if (session('success'))
            <p class="text-green-600 mb-4">{{ session('success') }}</p>
        @endif

        <!-- Bảng Hiển Thị Check-in -->
        <table class="min-w-full table-auto border-collapse border border-gray-200">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border border-gray-300 p-2 text-left">Date</th>
                    <th class="border border-gray-300 p-2 text-left">Username</th>
                    <th class="border border-gray-300 p-2 text-left">Type</th>
                    <th class="border border-gray-300 p-2 text-left">Time</th>
                    {{-- <th class="border border-gray-300 p-2 text-left">Signature</th> --}}
                </tr>
            </thead>
            <tbody>
                @foreach ($checkins as $checkin)
                    <tr class="border-b border-gray-200 hover:bg-gray-100 cursor-pointer"
                        onclick="openModal({{ json_encode($checkin) }})">
                        <td class="p-2">{{ $checkin->date }}</td>
                        <td class="p-2">{{ $checkin->username }}</td>
                        <td class="p-2">{{ $checkin->type }}</td>
                        <td class="p-2">{{ \Carbon\Carbon::parse($checkin->start_time)->format('H:i') }} ~ {{ \Carbon\Carbon::parse($checkin->end_time)->format('H:i') }}</td>
                        {{-- <td class="p-2">
                            <img src="{{ asset('/' . $checkin->signature) }}" alt="Signature" class="w-16 h-16 object-cover">
                        </td> --}}
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4">
            {{ $checkins->links() }}
        </div>

        <!-- Modal Hiển Thị Chi Tiết -->
        <div id="modal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 hidden">
            <div class="bg-white p-6 rounded-lg w-96 relative">
                <!-- Nút X để đóng Modal -->
                <button onclick="closeModal()"
                    class="absolute top-2 right-2 text-4xl text-gray-600 hover:text-gray-900 focus:outline-none">
                    &times;
                </button>
                <h2 class="text-xl font-semibold mb-4">Check-in Details</h2>
                <div id="modal-content">
                    <!-- Nội dung chi tiết sẽ được chèn qua JavaScript -->
                </div>
            </div>
        </div>
    </div>

    <script>
        function openModal(checkin) {
            // Hiển thị modal
            document.getElementById('modal').classList.remove('hidden');

            // Định dạng lại start_time và end_time để không hiển thị giây
            const formatTime = (time) => {
                const [hours, minutes] = time.split(':');
                return `${hours}:${minutes}`;
            };

            // Chèn thông tin chi tiết vào modal
            const modalContent = `
        <p><strong>Date:</strong> ${checkin.date}</p>
        <p><strong>Username:</strong> ${checkin.username}</p>
        <p><strong>Type:</strong> ${checkin.type}</p>
        <p><strong>Start Time:</strong> ${formatTime(checkin.start_time)}</p>
        <p><strong>End Time:</strong> ${formatTime(checkin.end_time)}</p>
        <div>
            <strong>Signature:</strong>
            <img src="/${checkin.signature}" class="w-100 h-100 object-cover mt-1">
        </div>
    `;
            document.getElementById('modal-content').innerHTML = modalContent;
        }

        function closeModal() {
            // Ẩn modal
            document.getElementById('modal').classList.add('hidden');
        }
    </script>
</body>

</html>
