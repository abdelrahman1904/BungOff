<?php
$apiKey = "d93045de3c0e16917273c303e4192f6d";
$city = "Tunis";

// Récupération des données météo
$weatherUrl = "https://api.openweathermap.org/data/2.5/forecast?q={$city}&units=metric&lang=fr&appid={$apiKey}";
$response = file_get_contents($weatherUrl);
$data = json_decode($response, true);

if (!$data || $data['cod'] != 200) {
    die("Impossible de récupérer les données météo.");
}

// Traitement des données
$current = $data['list'][0];
$timezoneOffset = $data['city']['timezone'] ?? 0;
$timezone = new DateTimeZone(timezone_name_from_abbr('', $timezoneOffset, 0));

// Météo actuelle
$currentData = [
    'temp' => round($current['main']['temp']),
    'icon' => $current['weather'][0]['icon'],
    'desc' => ucfirst($current['weather'][0]['description']),
    'feels_like' => round($current['main']['feels_like']),
    'humidity' => $current['main']['humidity'],
    'wind' => round($current['wind']['speed'] * 3.6),
    'pressure' => $current['main']['pressure'],
    'sunrise' => date('H:i', $data['city']['sunrise'] + $timezoneOffset),
    'sunset' => date('H:i', $data['city']['sunset'] + $timezoneOffset)
];

// Prévisions sur 3 jours
$forecastDays = [];
$processedDays = [];
$frenchDays = ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];

foreach ($data['list'] as $forecast) {
    $date = new DateTime($forecast['dt_txt']);
    $date->setTimezone($timezone);
    $day = $date->format('Y-m-d');
    
    if ($day == (new DateTime())->setTimezone($timezone)->format('Y-m-d')) continue;
    
    if (!isset($processedDays[$day]) && count($forecastDays) < 3) {
        $dayName = $frenchDays[$date->format('w')];
        $shortDayName = substr($dayName, 0, 3);
        
        $forecastDays[] = [
            'date' => $date->format('d/m'),
            'day_name' => $dayName,
            'short_day' => $shortDayName,
            'temp' => round($forecast['main']['temp']),
            'icon' => $forecast['weather'][0]['icon'],
            'desc' => ucfirst($forecast['weather'][0]['description']),
            'min_temp' => round($forecast['main']['temp_min']),
            'max_temp' => round($forecast['main']['temp_max'])
        ];
        $processedDays[$day] = true;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Météo - <?= htmlspecialchars($city) ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #4361ee;
            --secondary: #3f37c9;
            --accent: #4895ef;
            --light: #f8f9fa;
            --dark: #212529;
            --success: #4cc9f0;
            --warning: #f72585;
            --card-bg: rgba(255, 255, 255, 0.9);
            --text-light: #6c757d;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #e0f7fa, #b2ebf2);
            color: var(--dark);
            min-height: 100vh;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .weather-app {
            width: 100%;
            max-width: 900px;
            background: var(--card-bg);
            border-radius: 20px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            animation: fadeIn 0.5s ease-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .header {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            padding: 25px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 70%);
            transform: rotate(30deg);
        }
        
        .header h1 {
            font-size: 2rem;
            font-weight: 600;
            margin-bottom: 5px;
            position: relative;
        }
        
        .location {
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            position: relative;
        }
        
        .location i {
            margin-right: 8px;
            color: rgba(255, 255, 255, 0.8);
        }
        
        .current-weather {
            padding: 30px;
            display: flex;
            flex-direction: column;
            align-items: center;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }
        
        .weather-main {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            width: 100%;
            justify-content: center;
        }
        
        .weather-icon {
            width: 120px;
            height: 120px;
            margin-right: 20px;
            filter: drop-shadow(0 5px 10px rgba(0, 0, 0, 0.1));
        }
        
        .temperature {
            font-size: 4.5rem;
            font-weight: 300;
            color: var(--dark);
            line-height: 1;
        }
        
        .temperature sup {
            font-size: 2rem;
            vertical-align: super;
        }
        
        .weather-desc {
            font-size: 1.4rem;
            color: var(--primary);
            margin-bottom: 25px;
            font-weight: 500;
        }
        
        .weather-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            width: 100%;
        }
        
        .detail-card {
            background: white;
            border-radius: 12px;
            padding: 15px;
            display: flex;
            flex-direction: column;
            align-items: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .detail-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
        }
        
        .detail-icon {
            font-size: 1.5rem;
            color: var(--accent);
            margin-bottom: 8px;
        }
        
        .detail-label {
            font-size: 0.85rem;
            color: var(--text-light);
            margin-bottom: 5px;
        }
        
        .detail-value {
            font-size: 1.2rem;
            font-weight: 500;
            color: var(--dark);
        }
        
        .forecast-section {
            padding: 25px;
        }
        
        .section-title {
            font-size: 1.5rem;
            color: var(--dark);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }
        
        .section-title i {
            margin-right: 10px;
            color: var(--accent);
        }
        
        .forecast-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }
        
        .forecast-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .forecast-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            background: linear-gradient(135deg, #f8f9fa, white);
        }
        
        .forecast-day {
            font-size: 1rem;
            color: var(--text-light);
            margin-bottom: 5px;
        }
        
        .forecast-date {
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 15px;
        }
        
        .forecast-icon {
            width: 70px;
            height: 70px;
            margin: 10px 0;
            filter: drop-shadow(0 3px 6px rgba(0, 0, 0, 0.1));
        }
        
        .forecast-temp {
            font-size: 1.8rem;
            font-weight: 500;
            color: var(--primary);
            margin: 10px 0;
        }
        
        .temp-range {
            display: flex;
            justify-content: center;
            gap: 15px;
            width: 100%;
            margin-top: 5px;
        }
        
        .max-temp, .min-temp {
            font-size: 0.9rem;
            display: flex;
            align-items: center;
        }
        
        .max-temp::before {
            content: '↑';
            margin-right: 3px;
            color: var(--warning);
        }
        
        .min-temp::before {
            content: '↓';
            margin-right: 3px;
            color: var(--success);
        }
        
        .forecast-desc {
            font-size: 0.9rem;
            color: var(--text-light);
            text-align: center;
            margin-top: 10px;
        }
        
        @media (max-width: 768px) {
            .weather-main {
                flex-direction: column;
                text-align: center;
            }
            
            .weather-icon {
                margin-right: 0;
                margin-bottom: 15px;
            }
            
            .temperature {
                font-size: 3.5rem;
            }
            
            .forecast-container {
                grid-template-columns: 1fr;
            }
        }
        
        /* Animation pour les icônes météo */
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        
        .weather-icon, .forecast-icon {
            animation: float 3s ease-in-out infinite;
        }
    </style>
</head>
<body>
    <div class="weather-app">
        <div class="header">
            <h1>Météo Actuelle</h1>
            <div class="location">
                <i class="fas fa-map-marker-alt"></i>
                <span><?= htmlspecialchars($city) ?></span>
            </div>
        </div>
        
        <div class="current-weather">
            <div class="weather-main">
                <img src="https://openweathermap.org/img/wn/<?= $currentData['icon'] ?>@4x.png" 
                     alt="<?= $currentData['desc'] ?>" class="weather-icon">
                <div class="temperature"><?= $currentData['temp'] ?><sup>°C</sup></div>
            </div>
            
            <div class="weather-desc"><?= $currentData['desc'] ?></div>
            
            <div class="weather-details">
                <div class="detail-card">
                    <div class="detail-icon"><i class="fas fa-temperature-low"></i></div>
                    <div class="detail-label">Ressenti</div>
                    <div class="detail-value"><?= $currentData['feels_like'] ?>°C</div>
                </div>
                
                <div class="detail-card">
                    <div class="detail-icon"><i class="fas fa-tint"></i></div>
                    <div class="detail-label">Humidité</div>
                    <div class="detail-value"><?= $currentData['humidity'] ?>%</div>
                </div>
                
                <div class="detail-card">
                    <div class="detail-icon"><i class="fas fa-wind"></i></div>
                    <div class="detail-label">Vent</div>
                    <div class="detail-value"><?= $currentData['wind'] ?> km/h</div>
                </div>
                
                <div class="detail-card">
                    <div class="detail-icon"><i class="fas fa-sun"></i></div>
                    <div class="detail-label">Lever</div>
                    <div class="detail-value"><?= $currentData['sunrise'] ?></div>
                </div>
                
                <div class="detail-card">
                    <div class="detail-icon"><i class="fas fa-moon"></i></div>
                    <div class="detail-label">Coucher</div>
                    <div class="detail-value"><?= $currentData['sunset'] ?></div>
                </div>
            </div>
        </div>
        
        <div class="forecast-section">
            <h2 class="section-title"><i class="fas fa-calendar-alt"></i> Prévisions sur 3 jours</h2>
            
            <div class="forecast-container">
                <?php foreach ($forecastDays as $forecast): ?>
                    <div class="forecast-card">
                        <div class="forecast-day"><?= $forecast['day_name'] ?></div>
                        <div class="forecast-date"><?= $forecast['date'] ?></div>
                        <img src="https://openweathermap.org/img/wn/<?= $forecast['icon'] ?>@2x.png" 
                             alt="<?= $forecast['desc'] ?>" class="forecast-icon">
                        <div class="forecast-temp"><?= $forecast['temp'] ?>°C</div>
                        
                        <div class="temp-range">
                            <div class="max-temp"><?= $forecast['max_temp'] ?>°C</div>
                            <div class="min-temp"><?= $forecast['min_temp'] ?>°C</div>
                        </div>
                        
                        <div class="forecast-desc"><?= $forecast['desc'] ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</body>
</html>