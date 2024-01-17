let userprogess = [];

// let examCode = '2954SJNV';
let examCode = prompt("Nhập mã đề thi", "2954SJNV") ?? '';
document.querySelector("#examCode").textContent = examCode;
let exam;
let total_question = 30;
let data;

async function getExam(code) {
    let response = await window.axios.get(examUrl + code);
    if (response.data.status) {
        return response.data.data;
    }
    return null;
}

(async function () {
    while (!exam) {
        exam = await getExam(examCode);
        if (!exam) {
            examCode = prompt("Mã đề thi không hợp lệ. Nhập lại mã đề thi", "2954SJNV") ?? '';
        }
    }
    document.querySelector("#examCode").textContent = examCode;

    getResults();
    setInterval(getResults, 5000);
})();

let interval;
const getResults = (users = undefined) => {
    if (!users) {
        window.axios.get(liveScoreUrl + `refesh/` + examCode)
            .then(function (response) {

                clearInterval(interval);

                data = response.data;

                return window.axios.get(examUrl + 'get-question-count/' + examCode);
            })

            .then(response => {

                total_question = response.data.question_count ?? 30;

                return window.axios.post(resultUrl + examCode, {
                    data: data,
                })
            })

            .then(response => {
                userprogess = response.data;
                clearInterval(interval)
                errorssort = 0;
                document.querySelector("#totalUser").textContent = userprogess.length;
                updateTable();
                // Cập nhật bảng mỗi giây
                interval = setInterval(updateTable, 1000);
            })

            .catch(function (error) {
                clearInterval(interval);
                console.log(error);
            });
    } else {
        userprogess = users;
        updateTable();
        // Cập nhật bảng mỗi giây
        interval = setInterval(updateTable, 1000);
    }
}

const contentMain = document.querySelector("#content-progess");

class ProgressBar {
    constructor(progressBarId, percentageId, targetPercentage) {
        this.width = 0;
        this.progressBar = document.getElementById(progressBarId);
        this.percentage = document.getElementById(percentageId);
        this.targetPercentage = targetPercentage;
    }

    animate() {
        if (this.width < this.targetPercentage) {
            this.width++;
            this.progressBar.style.width = this.width + "%";
            if (this.percentage) {
                this.percentage.textContent = this.width + "%";
            }
        }
    }
}

function setProgress(accuracy) {
    let progressBar1 = new ProgressBar("progressBar1", "percentage1", accuracy);
    let progressBar2 = new ProgressBar("progressBar2", null, 100 - accuracy);

    function animateProgressBars() {
        requestAnimationFrame(animateProgressBars);
        progressBar1.animate();
        progressBar2.animate();
    }

    animateProgressBars();
}

let totsort = 0;
let errorssort = 0;

function updateTable() {
    document.querySelector("#totalUser").textContent = userprogess.length;
    let lastSuccessScore = -1;
    if (errorssort < 1) {
        userprogess.sort((a, b) => b.total_point - a.total_point);
    } else {
        return;
    }


    const useritem = userprogess
        .map((user, index, array) => {
            let userClass = 'bg-white border-b';

            if (index === 0) {
                if (array.some((u, i) => i > index && user.total_point < u.total_point)) {
                    userClass += ' errors-row';
                    return;
                }
                errorssort++;
            } else if (user.total_point > lastSuccessScore) {
                userClass += ' errors-row';
                lastSuccessScore = user.total_point; // cập nhật điểm thành công cao nhất
            } else if (array.some((u, i) => i < index && user.total_point < u.total_point)) {
                userClass += ' success-row';
            }
            return `
                <tr class="${userClass}">
                    <th scope="row" class="px-6 py-4 w-[10%] font-medium text-gray-900 whitespace-nowrap">
                        ${index + 1}
                    </th>
                    <td class="px-6 py-4">
                        ${user.name}
                    </td>
                    <td class="px-6 py-4">
                        ${Number(user.total_time).toFixed(2)}
                    </td>
                    <td class="px-6 py-4">
                        ${user.total_point}
                    </td>
                    <td class="px-6 py-4 w-[50%]">
                        <div class="w-[100%] bg-[#f0f0f0] rounded-lg h-12 inline-flex">
                            <div class="bg-green-400 inline-flex ml-1.5 mt-1 h-10 rounded-l-lg" style="width: ${
                                    (Number(user.total_point) / Number(total_question)) * 100
                                }%">
                                <svg class="mt-2.5 ml-2 w-4 h-4 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 17 20">
                                    <path d="M7.958 19.393a7.7 7.7 0 0 1-6.715-3.439c-2.868-4.832 0-9.376.944-10.654l.091-.122a3.286 3.286 0 0 0 .765-3.288A1 1 0 0 1 4.6.8c.133.1.313.212.525.347A10.451 10.451 0 0 1 10.6 9.3c.5-1.06.772-2.213.8-3.385a1 1 0 0 1 1.592-.758c1.636 1.205 4.638 6.081 2.019 10.441a8.177 8.177 0 0 1-7.053 3.795Z"/>
                                </svg>
                                <p class="pl-0.5 pt-2 font-bold text-white">${user.total_point}</p>
                            </div>
                            <div class="bg-red-400 mt-1 h-10 rounded-r-lg" style="width: ${
                                    100 - (Number(user.total_point) / Number(total_question)) * 100
                                }%"></div>
                        </div>
                    </td>
                </tr>
                `
        })
        .join("");

    document.querySelector("#table-body").innerHTML = useritem;
}

window.Echo.channel('result-live-score.' + examCode)
    .listen('ResultLiveScoreEvent', function (response) {
        // Xử lý dữ liệu nhận được tại đây

        userprogess = response.data;
        errorssort = 0;
        updateTable();
    });
