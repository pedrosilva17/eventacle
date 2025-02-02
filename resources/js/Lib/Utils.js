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

export const dateTimeOptions = {
	weekday: 'short',
	year: 'numeric',
	month: 'short',
	day: 'numeric',
	hour: 'numeric',
	minute: 'numeric',
	timeZoneName: 'short',
};

export const SINGLE_POINTS_HELP = 'Single point scoring is simple: every contest is worth a point.';

export const CONFIDENCE_POINTS_HELP =
	'Confidence point scoring prompts the user to order their predictions based on how confident they are to get each one right, from 1 (least confident) up to the number of contests (most confident), assigning that score to each prediction. For every correct prediction, their confidence score is added to the total. Useful to make ties less common.';
