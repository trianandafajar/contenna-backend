<?php

if (!function_exists('updateMessage')) {
    /**
     * Generate update message.
     *
     * @param  bool  $success
     * @return array
     */
    function updateMessage($success)
    {
        if ($success) {
            return [
                "status" => "success",
                "message" => "Data updated successfully"
            ];
        } else {
            return [
                "status" => "failed",
                "message" => "Data failed to update!"
            ];
        }
    }
}

if (!function_exists('createMessage')) {
    /**
     * Generate create message.
     *
     * @param  bool  $success
     * @return array
     */
    function createMessage($success)
    {
        if ($success) {
            return [
                "status" => "success",
                "message" => "Data created successfully"
            ];
        } else {
            return [
                "status" => "failed",
                "message" => "Data failed to create!"
            ];
        }
    }
}

if (!function_exists('deleteMessage')) {
    /**
     * Generate delete message.
     *
     * @param  bool  $success
     * @return array
     */
    function deleteMessage($success)
    {
        if ($success) {
            return [
                "status" => "success",
                "message" => "Data deleted successfully"
            ];
        } else {
            return [
                "status" => "failed",
                "message" => "Data failed to delete!"
            ];
        }
    }
}
if (!function_exists('formatRupiah')) {

    function formatRupiah($amount = NULL)
    {
        if ($amount === NULL || $amount === 0) {
            return 'Rp 0';
        }
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }
}
