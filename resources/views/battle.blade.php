<x-layout>
    <x-menu></x-menu>
    
    {{$player1}} VS {{$player2}}<br>
    {{$game_counter}}<br>
<div class="flex justify-center">
    <table class="border-collapse border border-slate-900">
        <form action="/battle" method="post">
        @csrf
            @for($i=1; $i<=14; $i++)
            <tr class="border border-slate-900">
                @for($j=1; $j<=14; $j++)
                <?php $squ_num = $j + 14 * ($i - 1); ?>
                    <td class="border border-slate-900 w-8 h-8 text-center bg-amber-100" >
                        
                        <button type="submit" name="btn_num" value="{{$squ_num}}">
                            @if($squ_arr[$squ_num] == 0)
                            .
                            @elseif($squ_arr[$squ_num] == 1)
                            ◯
                            @elseif($squ_arr[$squ_num] == 2)
                            ●
                            @endif
                            {{$squ_num}}
                        </button>
                        <input type="hidden" name="{{$squ_num}}" value="{{$squ_arr[$squ_num]}}">
                        <input type="hidden" name="game_counter" value="{{$game_counter}}">
                        <input type="hidden" name="player1" value="{{$player1}}">
                        <input type="hidden" name="player2" value="{{$player2}}">
                    </td>
                    @endfor  
                </tr>
                @endfor
            </form>
    </table>
    
</div>


</x-layout>
