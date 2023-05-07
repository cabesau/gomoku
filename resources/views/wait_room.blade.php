<x-layout>
    <x-menu></x-menu>

    <h1 class="text-xl text-center p-12">待機室</h1><br>

    <div class="flex flex-col">
        <div class="flex">
            <div class="rounded border bg-yellow-500 p-3 flex-1">ルームNo:{{$room->room_no}}</div>
            <div class="rounded border bg-yellow-500 p-3 flex-1">ゲームモード：
                @if ($room->exciting_flg == 0)
                    通常モード
                @else
                    エキサイティングモード
                @endif

            </div>
        </div>
        <div class="rounded border bg-yellow-500 p-3">
            @if ($room_maker)
                対戦相手が来るまでお待ちください
            @else
                対戦相手:{{$room->user->name}}
            @endif
        </div>
        <div class="rounded border bg-yellow-500 p-3">コメント:{{$room->comment}}</div>

        @if ($room_maker)
        <form action="game_start" method="post">
        @csrf
            <input type="hidden" name="room_no" value={{$room->room_no}}>
            <div class="flex justify-center">
                <button type="submit" id="createRoomBtn" style="display:none" class="mb-8 py-3 px-4 mt-10 inline-flex justify-center items-center gap-2 rounded-md border border-transparent font-semibold bg-gray-500 text-white hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all text-sm dark:bg-gray-700 dark:hover:bg-gray-600 dark:focus:ring-offset-gray-800"id="createRoomBtn">
                    ゲームを開始する
                </button> 
            </div>  
        </form>
        @endif
    </div>

    <script>
        //二人揃ったらスタートボタンを表示させる
        function check_players_in_room(room_no) {
            $.get('/check_players_in_room', {room_no: room_no}, function(response) {
                if (response === 'success') {
                     // ボタンを表示する
                    $('#createRoomBtn').show();
                    console.log("success");
                } else {
                    // 新しいデータがない場合は、2秒後に再度ポーリングする
                    setTimeout(function() {
                        check_players_in_room(room_no);
                        console.log("NOTsuccess");
                    }, 2000);
                }
            });
        }

        //相手が開始ボタンを押したらゲームを開始させる
        function check_players_in_room(room_no) {
            $.get('/check_players_in_room', {room_no: room_no}, function(response) {
                if (response === 'success') {
                     // ボタンを表示する
                    $('#createRoomBtn').show();
                    console.log("success");
                } else {
                    // 新しいデータがない場合は、2秒後に再度ポーリングする
                    setTimeout(function() {
                        check_players_in_room(room_no);
                        console.log("NOTsuccess");
                    }, 2000);
                }
            });
        }

        // 二人揃ったかどうか確認
        let room_no = {{$room->room_no}}
        check_players_in_room(room_no);

        //開始ボタンが押されたかどうか確認
    </script>

</x-layout>