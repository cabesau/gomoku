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
                    <td class="border border-slate-900 w-8 h-8 text-cente">
                    <img id={{$squ_num}} class="squ_btn py-1px" src="" style="height: 100%">
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
    let room_maker_flg = {{$room_maker_flg}};
    const wail_comment = 'お待ちください';
    const your_turn_comment = 'あなたの番です';

    //初回jsonファイル取得
    check_json(room_no);

    //////////////////////メソッド//////////////////////

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

    //盤面に白か黒の石を表示
    function decide_color(data){
        for(let i = 1; i <= 196; i++){
            if(data[`squ_${i}`] == 0){
                $('#' + i).attr('src','{{asset('storage/white.svg')}}');
            }else if(data[`squ_${i}`] == 1){
                $('#' + i).attr('src','{{asset('storage/black_stone.svg')}}');
                $('#' + i).removeClass('squ_btn');
            }else{
                $('#' + i).attr('src','{{asset('storage/gold.svg')}}');
                $('#' + i).removeClass('squ_btn');
            }

        }
    }

    //手番プレイヤーを表示
    function display_turn_player(turn_player){
        if({{$room_maker_flg}} == 1){
            if(turn_player == 1){
                $("#turn_player").text(your_turn_comment);
            }else{
                $("#turn_player").text(wail_comment);
            }
        }else{
            if(turn_player == 2){
                $("#turn_player").text(your_turn_comment);
            }else{
                $("#turn_player").text(wail_comment);
            }
        }
    }
    
    //ゲームを終了させる
    function finish_game(room_no,win_player) {
        //表示を変更
        $("#turn_player").text(`${win_player}さんの勝利です`);
        
        //データを更新
        $.get('/update_finish_info', {room_no: room_no}, function(response) {
            if (response == 'success') {
                console.log('ゲームが終了しました');
            }
        });
    }

    //石を置く
    $('.squ_btn').on('click',function(){
        console.log($(this).attr('id') + 'のボタンが押されました');
        let room_no = {{$room_no}};
        
        if($('#turn_player').text() == your_turn_comment){
            let data = {
                room_no: room_no,
                room_maker_flg: room_maker_flg,
                squ_num: $(this).attr('id')
            };
            $.post('/battle_cal',data);
        }
    })

    //////////////////////アニメーション//////////////////////

    //石が置かれていないマスにマウスが置かれたら色が変わる
    $('.squ_btn').mouseover(function(){
        console.log($(this).attr('id') + 'にマウスが置かれました');
        if($(this).hasClass('squ_btn')){
            $(this).css('background-color','#ffd6d6');
        }
    });
    $('.squ_btn').mouseout(function(){
        $(this).css('background-color','');
    })
</script>

</x-layout>
