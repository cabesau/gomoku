<x-layout title="TOP | エキサイティング五目並べ">
<div class="p-4">

    <h1 class="text-xl text-center p-12">エキサイティング五目並べ</h1><br>

    <div class="flex flex-col">
        <div class="flex justify-center">
            <button type="submit" class="mb-8 py-3 px-4 inline-flex justify-center items-center gap-2 rounded-md border border-transparent font-semibold bg-gray-500 text-white hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all text-sm dark:bg-gray-700 dark:hover:bg-gray-600 dark:focus:ring-offset-gray-800"id="createRoomBtn">
                ルームを作成
            </button>
        </div>

    <table class="w-full">
        <thead>
            <tr>
                <th class="w-1/4">ルームID</th>
                <th class="w-1/4">ルーム作成者</th>
                <th class="w-1/4">コメント</th>
                <th class="w-1/4"></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($rooms as $room)
            <form action="/wait" method="POST">
            @csrf
            <tr>
                <td class="border border-gray-300 p-2">{{$room->id}}</td>
                <td class="border border-gray-300 p-2">{{$room->user->name}}</td>
                <td class="border border-gray-300 p-2">{{$room->comment}}</td>
                <td class="border border-gray-300 p-2"><button type="submit" class="w-full text-blue-700">入室する</button></td>
                <input type="hidden" value="{{$room->id}}" name="room_id">
            </tr>
            </form>
            @endforeach
        </tbody>
    </table>
</div>

<!-- ルーム作成用モーダル -->
<div class="fixed z-10 inset-0 overflow-y-auto hidden" id="createRoomModal">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6" role="dialog" aria-modal="true" aria-labelledby="modal-headline">
            <h3 class="text-lg leading-6 font-medium text-gray-900 text-center" id="modal-headline">ルームを作成</h3><br>
            <form action="wait" method="post">
            @csrf
                <input type="hidden" name="make_room" value="value">
                <div class="mt-2">
                    <label for="room-comment" class="block text-sm font-medium text-gray-700"></label>
                    <textarea id="room-comment" name="comment" placeholder="ここにコメントを入れてください" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" ></textarea><br>
                </div>
                <input type="checkbox" name="mood" value="1">エキサイティングモード
                <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm" id="createRoomConfirmBtn">
                        ルームを作成
                    </button>
                    <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" id="createRoomCancelBtn">
                        キャンセル
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- JavaScriptのコード -->
<script>
    // ルーム作成ボタンをクリックした時の処理
    document.getElementById("createRoomBtn").addEventListener("click", function() {
        // モーダルを表示する
        document.getElementById("createRoomModal").classList.remove("hidden");
    });

    // ルーム作成確認ボタンをクリックした時の処理
    document.getElementById("createRoomConfirmBtn").addEventListener("click", function() {
        // ここにルームを作成する処理を記述する
        // ...

        // モーダルを非表示にする
        document.getElementById("createRoomModal").classList.add("hidden");
    });

    // ルーム作成キャンセルボタンをクリックした時の処理
    document.getElementById("createRoomCancelBtn").addEventListener("click", function() {
        // モーダルを非表示にする
        document.getElementById("createRoomModal").classList.add("hidden");
    });
</script>


</x-layout>