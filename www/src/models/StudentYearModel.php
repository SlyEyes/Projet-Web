<?php

namespace Linkedout\App\models;

use Linkedout\App\entities\StudentYearEntity;

class StudentYearModel extends BaseModel
{
    /**
     * Get all student years
     * @return StudentYearEntity[]|null
     */
    public function getStudentYears(): ?array
    {
        $sql = "SELECT studentYearId, studentYear FROM studentsYears";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();

        if (empty($result))
            return null;

        return array_map(fn($studentYear) => new StudentYearEntity($studentYear), $result);
    }

    /**
     * Get student years for a specified internship
     * @param int $internshipId
     * @return StudentYearEntity[]|null
     */
    public function getStudentYearsForInternship(int $internshipId): ?array
    {
        $sql = "SELECT studentsYears.studentYearId, 
                        studentsYears.studentYear 
                FROM internship_studentYear 
                INNER JOIN studentsYears ON internship_studentYear.studentYearId = studentsYears.studentYearId
                WHERE internshipId = :internshipId";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['internshipId' => $internshipId]);
        $result = $stmt->fetchAll();

        if (empty($result))
            return null;

        return array_map(fn($studentYear) => new StudentYearEntity($studentYear), $result);
    }

    /**
     * Add a student year for a specified internship
     * @param int $internshipId
     * @param int $studentYearId
     * @return bool `true` if the student year was added, `false` otherwise
     */
    public function addStudentYearForInternship(int $internshipId, int $studentYearId): bool
    {
        $sql = "INSERT INTO internship_studentYear (internshipId, studentYearId) VALUES (:internshipId, :studentYearId)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['internshipId' => $internshipId, 'studentYearId' => $studentYearId]);
        return $stmt->rowCount() > 0;
    }

    /**
     * Remove all student years for a specified internship
     * @param int $internshipId
     * @return int The number of student years removed
     */
    public function removeStudentYearsForInternship(int $internshipId): int
    {
        $sql = "DELETE FROM internship_studentYear WHERE internshipId = :internshipId";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['internshipId' => $internshipId]);
        return $stmt->rowCount();
    }
}
