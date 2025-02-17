import { clsx } from 'clsx';
import { twMerge } from 'tailwind-merge';

export function cn(...inputs) {
	return twMerge(clsx(inputs));
}

export const plural = (count, noun, suffix = 's') => `${count} ${noun}${count !== 1 ? suffix : ''}`;
export const needsBreakAll = (str, len) => {
	return str.split(' ').some((w) => {
		return w.length >= len;
	});
};
export const toggleAccordions = (htmlClass) => {
	const accordions = document.querySelectorAll(htmlClass);
	const newState = Array.from(accordions).some((acc) => acc.dataset.state === 'closed') ? 'open' : 'closed';
	accordions.forEach((element) => {
		if (element.dataset.state !== newState) element.click();
	});
};

export const beforeLeave = (el) => {
	const { marginLeft, marginTop, width, height } = window.getComputedStyle(el);
	el.style.left = `${el.offsetLeft - parseFloat(marginLeft, 10)}px`;
	el.style.top = `${el.offsetTop - parseFloat(marginTop, 10)}px`;
	el.style.width = width;
	el.style.height = height;
};

export const convertUtcToLocalString = (utcDateString) => {
	const date = new Date(utcDateString);

	const year = date.getFullYear();
	const month = String(date.getMonth() + 1).padStart(2, '0');
	const day = String(date.getDate()).padStart(2, '0');
	const hours = String(date.getHours()).padStart(2, '0');
	const minutes = String(date.getMinutes()).padStart(2, '0');

	return `${year}-${month}-${day}T${hours}:${minutes}`;
};

export const dateTimeOptions = {
	weekday: 'short',
	year: 'numeric',
	month: 'short',
	day: 'numeric',
	hour: 'numeric',
	minute: 'numeric',
	timeZoneName: 'short',
};

export const SINGLE_POINTS_HELP =
	'Single point scoring is simple: every contest awards you with a point if you guess correctly. The person with the most correct guesses wins!';

export const CONFIDENCE_POINTS_HELP =
	'With confidence point scoring, you must order your predictions based on how confident you are to guess each one correctly, from 1 (least confident) up to the number of contests (most confident). For every correct prediction, the confidence score you chose is added to your total score!';
