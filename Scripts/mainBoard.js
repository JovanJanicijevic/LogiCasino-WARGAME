    $(document).ready(function(){

        $('#logutBtn').click(function(e) {
            window.location.href = "../process.php?logout=true";
        });

        $('#dealCard').click(function(){
            // proveri dal je igra vec pocelA!;
            nBVal =  Number(document.getElementById("normalBetValue").innerHTML);
            tBVal = Number(document.getElementById("tieBetValue").innerHTML);
            $.ajax({
                type:"POST",
                url: "../api/api.php",
                dataType: 'json',
                data: {deal: "true", normalBet: nBVal, tieBet: tBVal},
                success:function(result){
                     if(result.bet != "BAD") {

                         document.getElementById("betInformation").innerText = result.bet;
                         document.getElementById("userCard").innerHTML = result.userCard[0] + '<br>' + result.userCard[1];
                         document.getElementById("dealerCard").innerHTML = result.dealerCard[0] + '<br>' + result.dealerCard[1];
                         document.getElementById("userBalance").innerText = result.balance;
                         if(result.bet == "war") {

                             r = confirm("Zelite li da ratujete?");
                             if (r == true) {
                                 warSettle('war');
                             } else {
                                 warSettle('fold');
                             }
                         }

                     }else {
                         document.getElementById("userCard").innerHTML ='X<br>X';
                         document.getElementById("dealerCard").innerHTML ='X<br>X';
                         document.getElementById("betInformation").innerText="Nema novca!";
                     }
                  },
                error:function(e){
                    alert('greska!');
                    document.getElementById("userCard").innerHTML ='X<br>X';
                    document.getElementById("dealerCard").innerHTML ='X<br>X';
                }

            });
        });

        $('#normalBetBtn').click(function(e){

            nBVal =  Number(document.getElementById("normalBetValue").innerHTML) + 50;
            tBVal = Number(document.getElementById("tieBetValue").innerHTML);

            checkBet(nBVal,tBVal)
        });

        $('#tieBetBtn').click(function(e){

            tBVal =  Number(document.getElementById("tieBetValue").innerHTML) + 2;
            nBVal = Number(document.getElementById("normalBetValue").innerHTML);

            checkBet(nBVal,tBVal);


        });

        function checkBet(normalBet, tieBet){

            $.ajax({
                type: "POST",
                url: "../api/bets.php",
                dataType: 'json',
                data: {tieBet: tieBet, normalBet: normalBet},
                success:function(result){
                    console.log(result.res);
                    if(result.res == 'OK') {
                        document.getElementById("tieBetValue").innerHTML = tBVal;
                        document.getElementById("normalBetValue").innerHTML = nBVal;
                    }else
                        alert(result.res);
                },
                error:function(){
                    alert('Could not be posted');
                    good = false;
                }
            });

        }


        $('#listBets').click(function(e){
            $.ajax({
                type: "POST",
                url: "../api/betList.php",
                dataType: 'json',
                data: {tieBet: "5"},
                success:function(result){
                    alert(result.res);
                },
                error:function(){
                    alert('Could not be posted');
                }
            });

        });


        function warSettle(decision) {
            $.ajax({
                type: "POST",
                url: "../api/war.php",
                dataType: 'json',
                data: {decision : decision},
                success:function(result){
                    if(result.res == 'fold') {
                        alert(result.res);
                        document.getElementById("userCard").innerHTML ='X<br>X';
                        document.getElementById("dealerCard").innerHTML ='X<br>X';
                        document.getElementById("betInformation").innerText="Folded!";
                    }
                    else if(result.res == 'war'){
                        alert('deli opet!');
                        document.getElementById("betInformation").innerText = result.bet;
                        document.getElementById("userCard").innerHTML = result.userCard[0] + '<br>' + result.userCard[1];
                        document.getElementById("dealerCard").innerHTML = result.dealerCard[0] + '<br>' + result.dealerCard[1];
                        document.getElementById("userBalance").innerText = result.balance;
                    }
                },
                error:function(){
                    alert('Could not be posted');
                }
            });
        }



    });

