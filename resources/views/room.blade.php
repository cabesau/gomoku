<x-layout>
    <div class="m-3">
        <form action={{route('top')}} method="post">
            @csrf
            <button id="top_btn" class="px-2 py-1 text-blue-500 border border-blue-500 font-semibold rounded hover:bg-blue-100">トップに戻る</button>
            <input type="hidden" name="room_maker_flg" value={{$room_maker_flg}}>
            <input type="hidden" name="room_no" value={{$room_no}}>
        </form>
    </div>

    <h1 class="text-xl text-center p-12">待機室</h1><br>

    <div class="flex flex-col">
        <div class="flex">
            <div class="rounded border bg-yellow-500 p-3 ml-1 flex-1">
                ルームNo:{{$room_no}}
            </div>
            <div class="rounded border bg-yellow-500 p-3 mr-1 flex-1">ゲームモード：
                <span id="game_mode"></span>
            </div>
        </div>
        <div class="flex">
            <div class="rounded border bg-yellow-500 p-3 ml-1 flex-2 w-1/2">
                <span id="Opponent"></span>
            </div>
            <div class="rounded border bg-yellow-500 p-3 mr-1 flex-2 w-1/2">トップに戻るまであと：
                <span id="timer"></span>
            </div>
        </div>

        <div class="rounded border bg-yellow-500 p-3 mx-1 ">コメント:
            <span id="comment"></span>
        </div>

        {{-- ゲーム開始ボタン --}}
        @if ($room_maker_flg)
            <p id="createRoomBtn" class="hidden w-40 mb-8 py-3 px-4 mt-10 justify-center items-center gap-2 rounded-md border border-transparent font-semibold bg-gray-500 text-white hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all text-sm dark:bg-gray-700 dark:hover:bg-gray-600 dark:focus:ring-offset-gray-800">
                <a href={{ route('battle', ['room_no'=>$room_no,'room_maker_flg'=>$room_maker_flg]) }}>ゲームを開始する</a>
            </p>
        @endif
    </div>

    <script>
        'use strict';

        let room_no = String({{$room_no}});
        let room_maker_flg = {{$room_maker_flg}};

        //初回jsonファイル取得
        check_json(room_no);

        //ajax通信でjsonファイルを取得
        function check_json(room_no){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: '/return_json',
            type: 'POST',
            dataType: 'json',
            data:{room_no: room_no},
        })
        .done( function(json_data) {
            // 取得したJSONデータを表示
            console.log('取得できました');
            let data = json_data[0];
            let delete_room_time = data['delete_room_time'];
            let room_no = data['room_no'];

            display_info(data);
            display_timer(delete_room_time,room_no);

            if(room_maker_flg === 1){
                check_players_in_room(data);
            }else{
                check_game_started(data)
            }

            //1秒後にポーリング
            setTimeout(function(){
                check_json(room_no);
            } ,1000,room_no);
            
        })
        .fail( function(jqXHR, textStatus, errorThrown) {
            console.log('失敗');
            console.log("ajax通信に失敗しました");
            console.log("jqXHR          : " + jqXHR.status); // HTTPステータスが取得
            console.log("textStatus     : " + textStatus);    // タイムアウト、パースエラー
            console.log("errorThrown    : " + errorThrown); // 例外情報
            console.log("URL            : " + '/return_json');
        });
      }

        //画面の情報を表示させる
        function display_info(data){
            $('#game_mode').text(function(){
                if(data['exciting_flg'] === 0){
                    return '通常モード';
                }else{
                    return 'エキサイティングモード';
                }
            });
            $('#Opponent').text(function(){
                if(room_maker_flg === 0){
                    return '対戦相手'+data['player1_name'];
                }else{
                    return '対戦相手が来るまででお待ちください';
                }
            });
            $('#comment').text(data['comment']);
        }
        
        //タイマーを表示させる
        function display_timer(delete_room_time,room_no){
            let now = new Date();
            let js_time_stamp = Math.floor(now.getTime() / 1000);
            let timer = delete_room_time - js_time_stamp;
            let timer_min = Math.floor(timer / 60);
            let timer_sec = timer % 60;
            
            if(timer <= 0){
                return_top(room_no);
            }
            $('#timer').text(timer_min + '分' +  timer_sec + '秒');
        }
        
        //トップ画面に戻る
        function return_top(room_no){
            //データを更新
            $.get('/update_finish_info', {room_no: room_no}, function(response) {
                if (response == 'success') {
                    console.log('success');
                }
            });
            //トップに画面遷移
            window.location.href = 'http://localhost/top';
        }

        //二人揃ったらスタートボタンを表示させる
        function check_players_in_room(data) {
            if(data['opponent_flg'] == 1){
                // ボタンを表示する
               $('#createRoomBtn').show();
            }else{
                //ボタンを隠す
                $('#createRoomBtn').hide();
            }
            
        }

        //ゲーム開始ボタンが押されたらバトル画面に遷移する
        function check_game_started(data) {
            if(data['start_flg'] == 1){
                window.location.href = `http://localhost/battle/${room_no}/0`;
            }
        }

    </script>

</x-layout>