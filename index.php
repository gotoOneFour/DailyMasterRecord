<?php

require("setting.php");

try{
	
	$options = [
		PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
	];

	$pdo = new PDO($dsn, $username, $password, $options);
	
	$s = $pdo->prepare("SELECT * FROM {$tablename}");
    
	$s->execute();
    
	$resultJson = json_encode($s->fetchAll());
	
} catch (PDOException $e) {
	exit($e->getMessage());
}
?>

<html lang="ja">
<head>
    <title>XXXXXXXXXXXXXX</title>
    <meta charset="UTF-8">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.css"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js"></script>
</head>
<body>
    <main>
        <section>
            <canvas id="barGraph"></canvas>
        </section>
    </main>
    <script>
        var getDateString = function(date){
            return (date.getFullYear()+"/"+(date.getMonth()+1)+"/"+date.getDate());
        }
        
        var result = <?= $resultJson ?>;
        console.log(result);
        var startDate = new Date(getDateString(new Date(result[0].datetime)));
        console.log(startDate);
        var endDate = new Date(getDateString(new Date(Date.now())));
        console.log(endDate);
        var dates = {};
        for(let d = startDate;d<=endDate;d.setDate(d.getDate()+1)){
            dates[getDateString(d)]={count:0,content:[]};
        }
        console.log(dates);
        for(let i=0;i<result.length;i++){
            let d = new Date(result[i].datetime);
            dates[getDateString(d)].count++;
            dates[getDateString(d)].content.push({
                genre: result[i].genre,
                content: result[i].content
            });
        }
        console.log(dates);
        var dates_keyArray = Object.keys(dates);
        var dates_valueArray = [];
        dates_keyArray.forEach(
            (key)=>{dates_valueArray.push(dates[key].count);});
        console.log(dates_keyArray);
        console.log(dates_valueArray);
        
        var ctx = document.getElementById("barGraph");
        var bar = new Chart(ctx,{
            type: "line",
            data: {
                labels: dates_keyArray,
                datasets: [
                    {
                        label: "射精数[回]",
                        data: dates_valueArray,
                        borderColor: "#00ff00",
                        //backgroundColor: "#ff00ff",
                    }
                ],
            },
            options: {
                title:{
                    display: true,
                    text: "日次射精数報告",
                },
                
                scales: {
                    yAxes: [{
                        ticks: {
                            suggestedMin: 0,
                            stepSize: 1,
                        }
                    }]
                },
                tooltips: {
                    callbacks: {
                        afterLabel: function (tooltipItem, data) {
                            let c = dates[tooltipItem.label].content;
                            let s = "";
                            for(let i=0;i<c.length;i++){
                                s+="ジャンル：" + c[i].genre + "　コンテンツ：" + c[i].content + "\n";
                            }
                            return s;
                        },
                    },
                },
            },
        });
        
    </script>
</body>
</html>