import {helpers} from "vuelidate/lib/validators";

export const serverError = function (attributeName, errorsDataVarName = 'responseErrors', validatorName = 'serverError') {
    return helpers.withParams(
        // Default message
        {message: 'Server error'},
        (value, vm) => {
            const errorIndex = vm[errorsDataVarName].findIndex(item => item.field === attributeName);
            const hasError = errorIndex !== -1;
            if (hasError) {
                vm.$nextTick(() => {
                    // Override default message
                    vm.$v[attributeName].$params[validatorName].message = vm[errorsDataVarName][errorIndex].message;
                });
            }

            return !hasError;
        },
    );
};
