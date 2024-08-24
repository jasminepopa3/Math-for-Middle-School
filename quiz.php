<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Math for Middle School</title>
    <link rel="stylesheet" href="styles_quiz.css">
</head>
<body>
    <header>
        <h1>Math for Middle School</h1>
    </header>
    <main>
        <div class="quiz-container">
            <div id="question-container" class="question"></div>
            <ul id="options-container" class="options"></ul>
            <button id="next-button" style="display: none;">Next</button>
            <div class="progress-bar">
                <div id="progress-bar-fill" class="progress-bar-fill"></div>
            </div>
        </div>
    </main>
    <footer>
        <p>&copy; 2024 Math for Middle School. Toate drepturile rezervate.</p>
    </footer>

    <script>
        let currentQuestionIndex = 0;
        let questions = [];
        let score = 0;

        document.addEventListener('DOMContentLoaded', () => {
            fetch('get_questions.php')
                .then(response => response.json())
                .then(data => {
                    questions = data;
                    showQuestion();
                });

            document.getElementById('next-button').addEventListener('click', () => {
                const selectedOption = document.querySelector('input[name="option"]:checked');
                if (selectedOption) {
                    const answer = parseInt(selectedOption.value, 10); // Conversie la număr întreg
                    const currentQuestion = questions[currentQuestionIndex];

                    saveAnswer(currentQuestion.id, answer, currentQuestion.correct_option);

                    if (answer === parseInt(currentQuestion.correct_option, 10)) {
                        score++;
                    }
                    currentQuestionIndex++;
                    if (currentQuestionIndex < questions.length) {
                        showQuestion();
                    } else {
                        showResults();
                    }
                }
            });
        });

        function showQuestion() {
            const questionContainer = document.getElementById('question-container');
            const optionsContainer = document.getElementById('options-container');
            const nextButton = document.getElementById('next-button');
            const progressBarFill = document.getElementById('progress-bar-fill');

            const currentQuestion = questions[currentQuestionIndex];

            let questionHTML = '';
            if (currentQuestion.image_url) {
                questionHTML += `<img src="${currentQuestion.image_url}" alt="Question Image">`;
            }
            questionHTML += `<p>${currentQuestion.question}</p>`;

            questionContainer.innerHTML = questionHTML;
            optionsContainer.innerHTML = '';
            for (let i = 1; i <= 4; i++) {
                const optionText = currentQuestion[`option${i}`];
                optionsContainer.innerHTML += `
                    <li>
                        <label>
                            <input type="radio" name="option" value="${i}">
                            ${optionText}
                        </label>
                    </li>
                `;
            }

            nextButton.style.display = 'block';
            progressBarFill.style.width = `${(currentQuestionIndex + 1) / questions.length * 100}%`;
        }

        function showResults() {
            const quizContainer = document.querySelector('.quiz-container');
            quizContainer.innerHTML = `
                <h2 style="text-align:center">You scored ${score} out of ${questions.length}</h2>
                <div class="centered-button-container">
                    <button onclick="window.location.href='index.php'">Go to Homepage</button>
                </div>
            `;
        }

        function saveAnswer(questionId, chosenOption, correctOption) {
            fetch('save_answer.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    question_id: questionId,
                    chosen_option: chosenOption,
                    correct_option: correctOption
                })
            })
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    console.error('Error saving answer:', data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
    </script>
</body>
</html>
