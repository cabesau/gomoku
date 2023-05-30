<x-layout>
    <x-menu></x-menu>
    
{{$json_data['player1_name']}}VS{{$json_data['player2_name']}}<br>
<div class="flex justify-center">
    <div class="flex flex-col">
        <div id="turn_player" class="cursor-pointer rounded border bg-yellow-500 pb-10 tex-center">
            @if ($json_data['win_player'])
            {{$json_data['win_player']}}の勝利
            @endif
        </div>
        <table class="border-collapse border border-slate-900">
            
            @for($i=1; $i<=14; $i++)
            <tr class="border border-slate-900">
                @for($j=1; $j<=14; $j++)
                <?php $squ_num = $j + 14 * ($i - 1); ?>
                <td class="border border-slate-900 w-8 h-8 text-center bg-amber-100 " >
                    @if (($json_data["squ_{$squ_num}"] == 0))
                    @if (!$json_data['win_player'])
                    <div  class="justify-center">
                        <a href={{ route('battle', ['room_no'=>$json_data['room_no'],'room_maker'=>$room_maker,'squ_num'=>$squ_num]) }}>
                            ・
                        </a> 
                        @else
                        .
                    </div>
                    @endif
                    @elseif($json_data["squ_{$squ_num}"] == 1)
                    ◯
                    @elseif($json_data["squ_{$squ_num}"] == 2)
                    ●
                    @endif
                    
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

    let room_maker = {{$room_maker}};
    console.log(room_maker);

    //ajax通信でjsonファイルを取得
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
    .done( function(response) {
        // 取得したJSONデータを表示
        console.log('取得できました');
        
        // 手番の制御
        let data = response[0];
        console.log(data.game_counter);
        console.log(data);
        
        turn_player(data.game_counter);
        // location.reload();

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

    function turn_player(game_counter){
        if(room_maker){
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

    
</script>

</x-layout>
