<x-layout>
    <form action={{route('top')}} method="post">
        @csrf
        <button id="top_btn" class="m-5 px-2 py-1 text-blue-500 border border-blue-500 font-semibold rounded hover:bg-blue-100">トップに戻る</button>
        <input type="hidden" name="room_maker_flg" value={{$room_maker_flg}}>
        <input type="hidden" name="room_no" value={{$room_no}}>
    </form>
<div>
    <span id="player1_name"></span>
    <span>VS</span>
    <span id="player2_name"></span>
</div>
<div>ルームno.{{$room_no}}</div>
<div class="flex justify-center">
    <div class="flex flex-col">
        <div id="turn_player" class="cursor-pointer rounded border bg-yellow-500 pb-10 tex-center"></div>
        <table class="border-collapse border border-slate-900">
            
            @for($i=1; $i<=14; $i++)
            <tr class="border border-slate-900">
                @for($j=1; $j<=14; $j++)
                <?php $squ_num = $j + 14 * ($i - 1); ?>
                <td class="border border-slate-900 w-8 h-8 text-center bg-amber-100 " >

                <a id={{$squ_num}} href={{ route('battle', ['room_no'=>$room_no,'room_maker_flg'=>$room_maker_flg,'squ_num'=>$squ_num]) }}></a> 
                    
                </td>
                @endfor 
            </tr>
            @endfor
        </table>
    </div>
</div>

<script>
    'use strict';

    let room_no = {{$room_no}};
    console.log(`ルームナンバーは${room_no}`);

    let room_maker = {{$room_maker_flg}};
    console.log('ルームメイカー：'+room_maker);

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
            let data = json_data[0];
            let win_player = data['win_player'];
            let game_counter = data['game_counter'];
            let player1_name = data['player1_name'];
            let player2_name = data['player2_name'];
            let room_no = data['room_no'];
            let turn_player = data['turn_player'];
            let delete_flg = data['delete_flg'];

            console.log('game_counter:'+game_counter);
            
            display_player(player1_name,player2_name);
            decide_color(data);
            
            if(win_player == 0){
                display_turn_player(turn_player);
                
            }else{
                finish_game(room_no,win_player);
            }
            
            //ゲームが終了していなければポーリング
            if(delete_flg == 0){
                setTimeout(function(){
                    check_json(room_no);
                } ,1000,room_no);
            }
            
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
    
    //プレイヤーの名前を表示
    function display_player(player1_name,player2_name){
        $("#player1_name").text(player1_name);
        $("#player2_name").text(player2_name);
    }

    //盤面に白か黒の石を置くメソッド
    function decide_color(data){
        for(let i = 0; i < 195; i++){
            if(data[`squ_${i}`] == 0){
                $(`#${i}`).text('・');
            }else if(data[`squ_${i}`] == 1){
                $(`#${i}`).text('●');
            }else{
                $(`#${i}`).text('◯');
            }
        }
    }

    //手番プレイヤーを表示
    function display_turn_player(turn_player){
        if({{$room_maker_flg}} == 1){
            if(turn_player == 1){
                $("#turn_player").text("あなたの番です");
                $('a').off('click'); // aタグのクリックイベントを有効にする
            }else{
                $("#turn_player").text("お待ちください");
                $('a').click(function(event) {
                    event.preventDefault(); //aタグのクリックイベントを無効にする
                });
            }
        }else{
            if(turn_player == 2){
                $("#turn_player").text("あなたの番です");
                $('a').off('click'); // aタグのクリックイベントを有効にする
            }else{
                $("#turn_player").text("お待ちください");
                $('a').click(function(event) {
                    event.preventDefault(); // aタグのクリックイベントを無効にする
                });
            }
        }
    }
    
    //ゲームを終了させる
    function finish_game(room_no,win_player) {
        //表示を変更
        $("#turn_player").text(`${win_player}さんの勝利です`);
        $('a').click(function(event) {
                event.preventDefault(); 
        });
        //データを更新
        $.get('/update_finish_info', {room_no: room_no}, function(response) {
            if (response == 'success') {
                console.log('success');
            }
        });
    }
    
</script>

</x-layout>
