// resources/js/password.js
import zxcvbn from 'zxcvbn';

export function checkPasswordStrength(password) {
    if (!password) {
        return { score: 0, feedback: { warning: '', suggestions: [] } };
    }
    return zxcvbn(password);
}

export function updateStrengthBar(passwordInputId, barId, textId) {
    const passwordInput = document.getElementById(passwordInputId);
    const bar = document.getElementById(barId);
    const text = document.getElementById(textId);
    
    if (!passwordInput || !bar || !text) return;
    
    passwordInput.addEventListener('input', (e) => {
        const val = e.target.value;
        const result = checkPasswordStrength(val);
        
        let color = 'bg-gray-700';
        let width = '0%';
        let label = 'Weak';
        
        if (val.length > 0) {
            switch(result.score) {
                case 0:
                case 1:
                    color = 'bg-red-500';
                    width = '25%';
                    label = 'Weak';
                    break;
                case 2:
                    color = 'bg-yellow-500';
                    width = '50%';
                    label = 'Fair';
                    break;
                case 3:
                    color = 'bg-blue-500';
                    width = '75%';
                    label = 'Good';
                    break;
                case 4:
                    color = 'bg-green-500';
                    width = '100%';
                    label = 'Strong';
                    break;
            }
        }
        
        bar.className = `h-2 rounded transition-all duration-300 ${color}`;
        bar.style.width = width;
        text.innerText = val.length > 0 ? `Password Strength: ${label}` : '';
        if (result.feedback.warning) {
            text.innerText += ` - ${result.feedback.warning}`;
        }
    });
}
