function auth() {
    let login = document.getElementById('login').value;
    let password = document.getElementById('password').value;

    if (login.trim().length == 0 || password.trim().length == 0) {
        alert('Все поля должны быть заполнены');
        return;
    }

    fetch('../api/v1/index.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json;charset=utf-8'
        },
        body: JSON.stringify({
            'function': 'auth',
            'login': login,
            'password': password
        })
    }).then((response) => {
        return response.text().then(function(data) {
            alert(data);
        });
    });
}

function registration() {
    let login = document.getElementById('login').value;
    let password = document.getElementById('password').value;
    let passwordSubmit = document.getElementById('password-submit').value;

    if (login.trim().length == 0 || password.trim().length == 0 || passwordSubmit.trim().length == 0) {
        alert('Все поля должны быть заполнены');
        return;
    }

    if (password != passwordSubmit) {
        alert('Пароль и повтор пароля должны быть одинаковы');
        return;
    }

    fetch('../api/v1/index.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json;charset=utf-8'
        },
        body: JSON.stringify({
            'function': 'registration',
            'login': login,
            'password': password
        })
    }).then((response) => {
        return response.text().then(function(data) {
            alert(data);
        });
    });
}

function logout() {
    fetch('../api/v1/index.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json;charset=utf-8'
        },
        body: JSON.stringify({
            'function': 'logout'
        })
    }).then((response) => {
        return response.text().then(function(data) {
            alert(data);
        });
    });
}