<x-layout>
    <form action={{route('top')}} method="post">
        @csrf
        <button id="top_btn" class="m-5 px-2 py-1 text-blue-500 border border-blue-500 font-semibold rounded hover:bg-blue-100">トップに戻る</button>
        <input type="hidden" name="room_maker_flg" value={{$room_maker_flg}}>
        <input type="hidden" name="room_no" value={{$json_data['room_no']}}>
    </form>
<div>
    <span id="player1_name"></span>
    <span>VS</span>
    <span id="player2_name"></span>
</div>
<div>ルームno.{{$json_data['room_no']}}</div>    
<div class="flex justify-center">
    <div class="flex flex-col">
        <div id="turn_player" class="cursor-pointer rounded border bg-yellow-500 pb-10 tex-center"></div>
        <table class="border-collapse border border-slate-900">
            
            @for($i=1; $i<=14; $i++)
            <tr class="border border-slate-900">
                @for($j=1; $j<=14; $j++)
                <?php $squ_num = $j + 14 * ($i - 1); ?>
                <td class="border border-slate-900 w-8 h-8 text-center bg-amber-100 " >

                <a id={{$squ_num}} href={{ route('battle', ['room_no'=>$json_data['room_no'],'room_maker_flg'=>$room_maker_flg,'squ_num'=>$squ_num]) }}></a> 
                    
                </td>
                @endfor  
            </tr>
            @endfor
        </table>
    </div>
</div>

<script>
    'use strict';

    let room_no = {{$json_data['room_no']}};
    console.log(`ルームナンバーは${room_no}`);

    let room_maker = {{$room_maker_flg}};
    console.log('ルームメイカー：'+room_maker);

    console.log('{{$json_data['win_player']}}');
    let win_player = '{{$json_data['win_player']}}';

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
            console.log('game_counter:'+data.game_counter);
            
            //プレイヤー名を表示
            display_player(data);
            
            //プレイヤーの名前を表示
            display_turn_player(data.game_counter);
            
            //盤面の描画
            decide_color(data);

            //5秒後にポーリング
            setTimeout(function(){
                check_json(room_no);
            } ,5000,room_no);
            
        })
        .fail( function(jqXHR, textStatus, errorThrown) {
            // console.error(error);
            console.log('失敗');
            console.log("ajax通信に失敗しました");
            console.log("jqXHR          : " + jqXHR.status); // HTTPステータスが取得
            console.log("textStatus     : " + textStatus);    // タイムアウト、パースエラー
            console.log("errorThrown    : " + errorThrown); // 例外情報
            console.log("URL            : " + '/return_json');
        });
    }
    
    //プレイヤーの名前を表示
    function display_player(data){
        $("#player1_name").text(data['player1_name']);
        $("#player2_name").text(data['player2_name']);
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

    //勝利プレイヤーか手番プレイヤーを表示
    function display_turn_player(game_counter){
        if(win_player != 0){
            $("#turn_player").text(`${win_player}さんの勝利です`);
            $('a').click(function(event) {
                    event.preventDefault(); 
            });
            finish_game(room_no);
        }else{
            if({{$room_maker_flg}} == 1){
                if(game_counter % 2 == 1){
                    $("#turn_player").text("あなたの番です");
                    $('a').off('click'); // aタグのクリックイベントの処理を解除して有効にする
                }else{
                    $("#turn_player").text("お待ちください");
                    $('a').click(function(event) {
                        event.preventDefault(); 
                    });
                }
            }else{
                if(game_counter % 2 == 0){
                    $("#turn_player").text("あなたの番です");
                    $('a').off('click'); // aタグのクリックイベントの処理を解除して有効にする
                }else{
                    $("#turn_player").text("お待ちください");
                    $('a').click(function(event) {
                        event.preventDefault();
                    });
                }
            }
        }
    }

    //ゲームを終了させる
    function finish_game(room_no) {
        $.get('/update_finish_info', {room_no: room_no}, function(response) {
            if (response === 'success') {
                //topボタンを切り替えたい
                console.log('success');
            }
        });
    }

    
</script>

</x-layout>
