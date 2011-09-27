<?php

class Jonckers_Validate_EndDateGreaterThanEqualStartDate extends Zend_Validate_Abstract {
    const NOT_GREATER_THAN_EQUAL = 'endDateGreaterThanEqualStartDate';

    protected $_messageTemplates = array(
        self::NOT_GREATER_THAN_EQUAL => 'End date must greater than start date'
    );

    protected $_fieldsToMatch = array();

    /**
     * Constructor of this validator
     *
     * The argument to this constructor is the third argument to the elements' addValidator
     * method.
     *
     * @param array|string $fieldsToMatch
     */
    public function __construct($fieldsToMatch = array()) {
        if (is_array($fieldsToMatch)) {
            foreach ($fieldsToMatch as $field) {
                $this->_fieldsToMatch[] = (string) $field;
            }
        } else {
            $this->_fieldsToMatch[] = (string) $fieldsToMatch;
        }
    }

    /**
     * Check if the element using this validator is valid
     *
     * This method will compare the $value of the element to the other elements
     * it needs to match. If they all greater than, the method returns true.
     *
     * @param $value string
     * @param $context array All other elements from the form
     * @return boolean Returns true if the element is valid
     */
    public function isValid($value, $context = null) {
        $value = (string) $value;
        $this->_setValue($value);

        $error = false;

        foreach ($this->_fieldsToMatch as $fieldName) {
            if (!isset($context[$fieldName]) || (date("Y-m-d", strtotime($value)) < date("Y-m-d", strtotime($context[$fieldName])))
			) {
                $error = true;
                $this->_error(self::NOT_GREATER_THAN_EQUAL);
                break;
            }
        }

        return !$error;
    }
}
