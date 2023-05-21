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
            <p class="hidden mb-8 py-3 px-4 mt-10 justify-center items-center gap-2 rounded-md border border-transparent font-semibold bg-gray-500 text-white hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all text-sm dark:bg-gray-700 dark:hover:bg-gray-600 dark:focus:ring-offset-gray-800"id="createRoomBtn">
                <a href={{ route('game', ['room_no'=>$room->room_no])}}>ゲームを開始する</a>
            </p>
        @endif
    </div>

    <script>
        'use strict';
        //二人揃ったらスタートボタンを表示させる
        function check_players_in_room(room_no) {
            $.get('/check_players_in_room', {room_no: room_no}, function(response) {
                if (response === 'success') {
                     // ボタンを表示する
                    $('#createRoomBtn').show();
                } else {
                    // 新しいデータがない場合は、2秒後に再度ポーリングする
                    setTimeout(function() {
                        check_players_in_room(room_no);
                    }, 2000);
                }
            });
        }

        //相手が開始ボタンを押したらゲームを開始させる
        function check_game_started(room_no) {
            $.get('/check_game_started', {room_no: room_no}, function(response) {
                if (response === 'success') {
                    //画面遷移したい
                    console.log('success');
                    window.location.href = `game/${room_no}`
                } else {
                    // 新しいデータがない場合は、2秒後に再度ポーリングする
                    setTimeout(function() {
                        console.log('no');
                        check_game_started(room_no);
                    }, 2000);
                }
            });
        }

        // 二人揃ったかどうか確認
        let room_no = {{$room->room_no}};
        check_players_in_room(room_no);

        //開始ボタンが押されたかどうか確認
        check_game_started(room_no);

        //戻るボタンを押したときにアラート表示
        $('#back_to_top').on('click',function(){
            window.confirm('本当に戻っていいですか？');
        });

        $('#taiki').on('click',function(){
            window.confirm('本当に戻っていいですか？');
        });

    </script>

</x-layout>