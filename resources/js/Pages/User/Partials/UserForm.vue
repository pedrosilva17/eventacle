<script setup>
import FormSection from '@/Components/FormSection.vue';
import InputError from '@/Components/InputError.vue';
import InputGroup from '@/Components/InputGroup.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Checkbox from '@/Components/Checkbox.vue';

const props = defineProps({
	form: {
		type: Object,
		required: true,
	},
	buttonText: {
		type: String,
		required: true,
	},
	creating: {
		type: Boolean,
		default: false,
	},
});

defineEmits(['submitted']);
</script>

<template>
	<FormSection @submit.prevent="$emit('submitted')">
		<template #form>
			<InputGroup>
				<InputLabel for="name">Name</InputLabel>
				<TextInput id="name" v-model="form.name" type="text" required autofocus />
				<InputError :message="form.errors.name" />
			</InputGroup>

			<InputGroup>
				<InputLabel for="email">Email</InputLabel>
				<TextInput id="email" v-model="form.email" type="email" required />
				<InputError :message="form.errors.email" />
			</InputGroup>

			<InputGroup>
				<InputLabel for="password">{{ creating ? 'Password' : 'New Password' }}</InputLabel>
				<TextInput id="password" v-model="form.password" type="password" :required="creating" />
				<InputError :message="form.errors.password" />
			</InputGroup>
			<InputGroup>
				<InputLabel for="password_confirmation">{{
					creating ? 'Confirm Password' : 'Confirm New Password'
				}}</InputLabel>
				<TextInput
					id="password_confirmation"
					v-model="form.password_confirmation"
					type="password"
					:required="creating"
					:disabled="!form.password"
				/>
				<InputError :message="form.errors.password_confirmation" />
			</InputGroup>
			<InputGroup v-if="$page.props.auth.user?.is_admin">
				<InputLabel for="is_admin">Admin account</InputLabel>
				<Checkbox
					id="is_admin"
					v-model:checked="form.is_admin"
					:disabled="$page.props.auth.user.id === form.id"
				/>
				<InputError :message="form.errors.is_admin" />
			</InputGroup>
		</template>

		<template #actions>
			<PrimaryButton :disabled="form.processing">
				{{ buttonText }}
			</PrimaryButton>
		</template>
	</FormSection>
</template>
