@php

$fullName = "Glen Paul Calimlim";
$title = "Information Technology Student";

$phone = "0993 181 0374";
$email = "glenpaulcalimlim@gmail.com";
$address = "Payas, Santa Barbara, Pangasinan";
$birthdate = "January 29, 2004";
$gender = "Male";
$nationality = "Filipino";
$age = 22;


if ($age == 21) {
    $ageText = "$age (Dalawampu’t isa)";
} elseif ($age >= 22 && $age <= 23) {
    $ageText = "$age (Dua pulo dua)";
} elseif ($age > 24) {
    $ageText = "$age (Duara nen lima ed say)";
} else {
    $ageText = $age;
}


$summary = "Motivated IT student with a strong interest in mobile and web development.
Eager to apply programming knowledge to real-world projects and continuously improve technical skills.";


$education = [
    [
        "year" => "2016 – 2020",
        "title" => "High School Diploma",
        "school" => "Payas National High School"
    ],
    [
        "year" => "2022 – Present",
        "title" => "Bachelor of Science in Information Technology",
        "school" => "College Student"
    ]
];

// EXPERIENCE
$experience = [
    [
        "year" => "2024 – Present",
        "title" => "Student Developer",
        "company" => "Academic Projects",
        "tasks" => [
            "Developed CRUD-based web systems using PHP and MySQL",
            "Created mobile applications using Flutter",
            "Worked with authentication and database integration"
        ]
    ]
];


$skills = ["PHP", "MySQL", "Flutter", "HTML", "CSS", "Java (Basic)"];
@endphp

<!DOCTYPE html>
<html>
<head>
<style>
body {
    font-family: Arial, sans-serif;
    background:#f2f4f7;
    margin:0;
}
.container {
    width:900px;
    margin:30px auto;
    background:#fff;
}


.header {
    background:#0b5394;
    color:white;
    display:flex;
    padding:25px;
}
.photo {
    width: 150px;
    height: 180px;
    object-fit: cover;
    border-radius: 4px;
    border: 2px solid #fff;
}

.header-main h1 {
    margin:0;
    font-size:32px;
}
.header-main p {
    margin:4px 0 15px;
}
.header-info {
    display:grid;
    grid-template-columns: 1fr 1fr;
    gap:10px 50px;
    font-size:14px;
}


.content {
    padding:25px;
}
.section-title {
    color:#0b5394;
    font-size:18px;
    border-bottom:2px solid #0b5394;
    padding-bottom:5px;
    margin-top:25px;
}
.row {
    display:flex;
    margin-top:15px;
}
.year {
    width:150px;
    font-weight:bold;
}
.details h4 {
    margin:0;
}
.details p {
    margin:3px 0;
}
ul {
    margin:5px 0 0 20px;
}
.skills ul {
    columns:2;
}
</style>
</head>
<body>

<div class="container">


<div class="header">
    <img src="{{ asset('images/glenpic.png') }}" class="photo">
<div>   </div>
    <div class="header-main">
        <h1> {{ $fullName }}</h1>
        <p> {{ $title }}</p>

        <div class="header-info">
            <span><strong>Phone:</strong> {{ $phone }}</span>
            <span><strong>Address:</strong> {{ $address }}</span>
            <span><strong>Email:</strong> {{ $email }}</span>
            <span><strong>Date of birth:</strong> {{ $birthdate }}</span>
            <span><strong>Age:</strong> {{ $ageText }}</span>
            <span><strong>Gender:</strong> {{ $gender }}</span>
            <span><strong>Nationality:</strong> {{ $nationality }}</span>
        </div>
    </div>
</div>


<div class="content">

    
    <p>{{ $summary }}</p>

   
    <div class="section-title">Education</div>
    @foreach($education as $edu)
    <div class="row">
        <div class="year">{{ $edu['year'] }}</div>
        <div class="details">
            <h4>{{ $edu['title'] }}</h4>
            <p>{{ $edu['school'] }}</p>
        </div>
    </div>
    @endforeach

   
    <div class="section-title">Experience</div>
    @foreach($experience as $exp)
    <div class="row">
        <div class="year">{{ $exp['year'] }}</div>
        <div class="details">
            <h4>{{ $exp['title'] }}</h4>
            <p><em>{{ $exp['company'] }}</em></p>
            <ul>
                @foreach($exp['tasks'] as $task)
                    <li>{{ $task }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endforeach

   
    <div class="section-title">Skills</div>
    <div class="skills">
        <ul>
            @foreach($skills as $skill)
                <li>{{ $skill }}</li>
            @endforeach
        </ul>
    </div>

</div>

</div>

</body>
</html>
